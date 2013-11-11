/*
archivist toolkit search


*/

-- function to get the resource for a given componentId
-- search for the parent resource starting from the component
-- first look at the resourceId for the resourcesComponents, if not null stop
-- else get the parentResourceComponentId for the component
-- get the resourceId for this component and if not null stop else get the parentResourceComponentId and loop until a resourceId is found
delimiter $
create function componentResourceParent(pComponentId bigint(20)) returns bigint(20)

begin
 declare vResourceId bigint(20);
 declare vComponentId bigint(20);
 declare done smallint;
 set done = 0;
 set vComponentId = pComponentId;
 
 while (done = 0) do
  select resourceId into vResourceId from resourcesComponents where resourceComponentId = vComponentId;
  if (vResourceId is null) then
   select parentResourceComponentId into vComponentId from resourcesComponents where resourceComponentId = vComponentId;
   if (vComponentId is null) then
    set vResourceId = 0;
	set done = 1;
   end if;
  else
   set done = 1;
  end if;
 end while;
 
 return vResourceId;
end $

delimiter ;

-- function to get the container for a component
-- if the component has more than one record, concat the data in container1, container2 and container3 for each record found
delimiter $
create function componentContainer(pComponentId bigint(20)) returns varchar(4000)

begin
 declare vInstance varchar(4000);
 declare s varchar(800);
 declare done int default false;
 declare c1 cursor for select concat(coalesce(concat(container1Type,': ',container1NumericIndicator,' ',container1AlphaNumIndicator),''),' ',coalesce(concat(container2Type,': ',container2NumericIndicator,' ',container2AlphaNumIndicator),''),' ',coalesce(concat(container3Type,': ',container3NumericIndicator,' ',container3AlphaNumIndicator),'')) from archdescriptioninstances where resourceComponentId = pComponentId;
 declare continue handler for not found set done = true;
 
 set vInstance = '';
 open c1;
 read_loop: loop
  fetch c1 into s;
  if (done) then
   leave read_loop;
  end if;
  set vInstance = concat(vInstance, s);
 end loop;
 
 return vInstance;
end $

delimiter ;

-- function to get the date for a resource
-- use each of the date fields: dateExpression, dateBegin and dateEnd, and bulkDateBegin and bulkDateEnd and return the first not null, return empty string if all null
-- @param pResourceId bigint - the resource to get the date for
-- @returns a string contaning the date
delimiter $
create function resourceDate(pResourceId bigint(20)) returns varchar(255)

begin
 declare vDateExpression varchar(255);
 declare vDateExpression1 varchar(255);
 declare vDateExpression2 varchar(255);
 declare vDateExpression3 varchar(255);
 
 select coalesce(dateExpression,''), concat(coalesce(dateBegin,''), ' - ', coalesce(dateEnd,'')), concat(coalesce(bulkDateBegin,''), ' - ', coalesce(bulkDateEnd,'')) into vDateExpression1, vDateExpression2, vDateExpression3 from resources where resourceId = pResourceId;
 if (length(vDateExpression1) > 0) then
  set vDateExpression = vDateExpression1;
 elseif (length(vDateExpression2) > 3) then
  set vDateExpression = vDateExpression2;
 elseif (length(vDateExpression3) > 3) then
  set vDateExpression = vDateExpression3;
 else
  set vDateExpression = '';
 end if;
 
 return vDateExpression;
end $

delimiter ;

-- function to get the date for a component
-- use each of the date fields: dateExpression, dateBegin and dateEnd, and bulkDateBegin and bulkDateEnd and return the first not null, return empty string if all null
-- @param pComponentId bigint - the component to get the date for
-- @returns a string containing the date
delimiter $
create function resourceComponentDate(pComponentId bigint(20)) returns varchar(255)

begin
 declare vDateExpression varchar(255);
 declare vDateExpression1 varchar(255);
 declare vDateExpression2 varchar(255);
 declare vDateExpression3 varchar(255);
 
 select coalesce(dateExpression,''), concat(coalesce(dateBegin,''), ' - ', coalesce(dateEnd,'')), concat(coalesce(bulkDateBegin,''), ' - ', coalesce(bulkDateEnd,'')) into vDateExpression1, vDateExpression2, vDateExpression3 from resourcesComponents where resourceComponentId = pComponentId;
 if (length(vDateExpression1) > 0) then
  set vDateExpression = vDateExpression1;
 elseif (length(vDateExpression2) > 3) then
  set vDateExpression = vDateExpression2;
 elseif (length(vDateExpression3) > 3) then
  set vDateExpression = vDateExpression3;
 else
  set vDateExpression = '';
 end if;
 
 return vDateExpression;
end $

delimiter ;

-- search procedure 
-- @param pSearchStr - string with max length 500 characters. 
-- @returns - a resultset with columns resourceId, resourceTitle, resourceIdentifier, componentId, componentTitle, componentInstance, dateExpression ordered by resourceTitle, componentInstance, componentTitle
-- the search string is a comma delimited list of search words. rlike is used to to the string comparisions to get the matching resources and components.
-- this was used to allow matching of the whole search word as entered and not a like search where part of a word would be matched
-- only resources with findingAidStatus = 'Completed' would be returned
delimiter $
create procedure searchFindingAids(pSearchStr varchar(500))

begin
 declare prevLoc integer;
 declare curLoc integer;
 declare len integer;
 declare punctString varchar(25);
 declare numRecs integer;
 
 -- temporary table to store the search terms
 drop temporary table if exists searchTerms;
 create temporary table searchTerms (
  termDesc varchar(500) COLLATE utf8_unicode_ci,
  termDesc1 varchar(500) COLLATE utf8_unicode_ci
 ) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
 
 drop temporary table if exists searchResultsIDS;
 create temporary table searchResultsIDS (
  resourceId bigint(20),
  componentId bigint(20)
 );
 
 -- temporary table to store results
 drop temporary table if exists searchResults;
 create temporary table searchResults (
  resourceId bigint(20),
  resourceTitle text COLLATE utf8_unicode_ci,
  resourceIdentifier varchar(20),
  componentId bigint(20),
  componentTitle text COLLATE utf8_unicode_ci,
  parentComponentId bigint(20),
  componentInstance varchar(4000),
  dateExpression varchar(255) COLLATE utf8_unicode_ci
 ) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
 
 set punctString = '-\' ."!?;:,/)'; -- punctuation allowed at start and end of the string that matches search term - one of these would match "
 
 -- break up the search string into individual words and store in the searchTerms temp table
 select length(pSearchStr) into len from dual;
 set prevLoc = 1;
 set curLoc = 1;
 while (curLoc < len) do
  select locate(',',pSearchStr,prevLoc) into curLoc from dual;
  if (curLoc > 0) then
   insert into searchTerms (termDesc1) values (substr(pSearchStr,prevLoc,curLoc-prevLoc));
   set prevLoc = curLoc + 1;
   set curLoc = curLoc + 1;
  else -- insert from prevLoc to end of string
   insert into searchTerms (termDesc1) values (substr(pSearchStr,prevLoc));
   set curLoc = len;
  end if;
 end while;
 
 -- add the punctuation string to the start and end of each of the search words and build a regular expression string to search with
 -- the search term and punctuation string are converted to the hexadecimal representation of the search string
 update searchTerms
 set termDesc = concat('(..)*[',hex(punctString),']',hex(ucase(termDesc1)),'[',hex(punctString),'](..)*');
 
 select count(*) into len from searchTerms; -- used to ensure that all the words entered are matched - an and search is done
 
 -- search resourcescomponents - title
 insert into searchResultsIDS(componentId)
 select a.resourceComponentId
 from resourcesComponents a
 where len = (select count(*) from searchTerms b where hex(concat(' ',ucase(a.title),' ')) rlike b.termDesc);
 
 -- subject keywords in archDescriptionSubjects
 insert into searchResultsIDS(resourceId, componentId)
 select a.resourceId, a.resourceComponentId
 from archdescriptionsubjects a, subjects b
 where a.subjectId = b.subjectId
 and len = (select count(*) from searchTerms c where hex(concat(' ',ucase(b.subjectTerm),' ')) rlike c.termDesc);
 
 -- names in archdescriptionnames
 insert into searchResultsIDS(resourceId, componentId)
 select a.resourceId, a.resourceComponentId
 from archdescriptionnames a, names b
 where a.primaryNameId = b.nameId
 and len = (select count(*) from searchTerms c where hex(concat(' ',ucase(b.sortName),' ')) rlike c.termDesc);
 
 -- add resources that have title that match
 insert into searchResultsIDS(resourceId)
 select a.resourceId
 from resources a
 where a.findingAidStatus = 'Completed'
 and len = (select count(*) from searchTerms b where hex(concat(' ',ucase(a.title),' ')) rlike b.termDesc);
 
 -- if no results found so far, search notes. this is a like search not exact word as those before
 select count(*) into numRecs from searchResultsIDS;
 if (numRecs = 0) then
  insert into searchResultsIDS(resourceId, componentId)
  select a.resourceId, a.resourceComponentId
  from archdescriptionrepeatingdata a
  where len = (select count(*) from searchTerms b where a.noteContent like concat('%',b.termDesc1,'%'));
 end if; 
 
 -- get the unique resourceID and componentID
 insert into searchResults(componentId)
 select componentId
 from searchResultsIDS
 where componentId is not null
 group by componentId;
 
 insert into searchResults(resourceId)
 select resourceId
 from searchResultsIDS
 where resourceId is not null
 group by resourceId;
 
 -- remove the resources that are not completed
 -- first get the parent resource for each component
 update searchResults a
 set resourceId = (select componentResourceParent(a.componentId) from dual)
 where componentId is not null;
 
 delete from searchResults
 where resourceId in (select resourceId from resources where findingAidStatus <> 'Completed');
 
 -- get the parent component id if any exists
 update searchResults a
 set parentComponentId = (select parentResourceComponentId from resourcesComponents b where b.resourceComponentId = a.componentId)
 where componentId is not null;
 
 -- get the title for the resources
 update searchResults a
 set resourceTitle = (select title from resources b where b.resourceId = a.resourceId);
 
 -- get the title for the components
 update searchResults a
 set componentTitle = (select title from resourcesComponents b where b.resourceComponentId = a.componentId)
 where componentId is not null;
 
 -- get dates of resources, search resourcesComponents first, if empty then use the one in resources
 update searchResults a
 set dateExpression = (select resourceComponentDate(a.componentId) from dual)
 where componentId is not null;
 
 update searchResults a
 set dateExpression = (select resourceDate(a.resourceId) from dual)
 where length(coalesce(dateExpression,'')) = 0;
 
 -- get the resource identifier for the resource
 update searchResults a
 set resourceIdentifier = (select resourceIdentifier1 from resources b where b.resourceId = a.resourceId);
 
 -- component instances
 update searchResults a
 set componentInstance = (select componentContainer(a.componentId) from dual)
 where componentId is not null;
 
 -- if the component does not have an instance, get the one attached to the parent
 update searchResults a
 set componentInstance = (select componentContainer(a.parentComponentId) from dual)
 where parentComponentId is not null
 and componentId is not null
 and componentInstance is null;
 
 -- return data
 select resourceId, resourceTitle, resourceIdentifier, componentId, componentTitle, componentInstance, dateExpression
 from searchResults
 order by resourceTitle, componentInstance, componentTitle;
end $

delimiter ;

-- get the creator name for a name id
-- @param pNameId bigint - the id of the name record
-- @result string - the name associated with the name id
-- the name returned would be the last one found from the name type list - Person, Corporate Body, Family. 
-- if name is not found, return empty string
delimiter $
create function creatorName(pNameId bigInt(20)) returns varchar(255) character set utf8

begin
 declare vName varchar(255) character set utf8;
 declare vNameType varchar(255) character set utf8; 
 
 set vName = '';
 
 select concat(personalPrefix, ' ', personalPrimaryName, ' ', personalRestOfName, ' ', personalSuffix, ' ', personalDates) into vName
 from names
 where nameId = pNameId
 and nameType = 'Person';
 
 select concat(corporatePrimaryName, ' ', corporateSubordinate1, ' ', corporateSubordinate2) into vName
 from names
 where nameId = pNameId
 and nameType = 'Corporate Body';
 
 select concat(familyNamePrefix, ' ', familyName) into vName
 from names
 where nameId = pNameId
 and nameType = 'Family';
 
 return vName;
end $

delimiter ;


-- procedure to return the resource detials, citation note for a resource
-- @param pResourceId bigint - the id of the resource
-- @result a resultset with the columns resourceId, resourceTitle, resourceIdentifier, dateExpression, displayRepository, extentDesc, languageCode, citationNote
delimiter $
create procedure findingAidItem(pResourceId bigInt(20))

begin
 -- temporary table for the resource
 drop temporary table if exists resourceData;
 create temporary table resourceData (
  resourceId bigint(20),
  resourceTitle text COLLATE utf8_unicode_ci,
  resourceIdentifier varchar(80),
  dateExpression varchar(255) COLLATE utf8_unicode_ci,
  displayRepository varchar(255) COLLATE utf8_unicode_ci,
  extentDesc varchar(300) COLLATE utf8_unicode_ci,
  languageCode varchar(255) COLLATE utf8_unicode_ci,
  citationNote text COLLATE utf8_unicode_ci
 ) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
 
 insert into resourceData (resourceId, resourceTitle, resourceIdentifier, dateExpression, displayRepository, extentDesc, languageCode)
 select resourceId, title, concat(resourceIdentifier1,resourceIdentifier2,resourceIdentifier3,resourceIdentifier4), resourceDate(resourceId), displayRepository, concat(extentNumber, ' ', extentType), languageCode
 from resources
 where resourceId = pResourceId
 and findingAidStatus = 'Completed';
 
 update resourceData a
 set citationNote = (select b.noteContent from archdescriptionrepeatingdata b inner join notesetctypes c on b.notesEtcTypeId = c.notesEtcTypeId where b.resourceId = a.resourceId and b.repeatingDataType = 'Note' and c.notesEtcName = 'Preferred Citation note')
 where exists (select 1 from archdescriptionrepeatingdata b inner join notesetctypes c on b.notesEtcTypeId = c.notesEtcTypeId where b.resourceId = a.resourceId and b.repeatingDataType = 'Note' and c.notesEtcName = 'Preferred Citation note');
 
 select resourceId, resourceTitle, resourceIdentifier, dateExpression, displayRepository, extentDesc, languageCode, citationNote
 from resourceData;
end $

delimiter ;

-- procedure to get the creator names for a resource
-- @param pResourceId bigint - the id of the resource
-- @result a resultset with the column creator
delimiter $
create procedure finindAidItemCreator(pResourceId bigInt(20))

begin
 select creatorName(b.primaryNameId)  as creator
 from archdescriptionnames b 
 where b.resourceId = pResourceId 
 and b.nameLinkFunction = 'Creator';
end $

delimiter ;

-- procedure to get the notes entered for a resource
-- @param pResourceId bigint - the id of the resource
-- @result a resultset with the columns noteContent and notesEtcLabel
delimiter $
create procedure findingAidItemNotes(pResourceId bigInt(20))

begin
 select b.noteContent, c.notesEtcLabel
 from archdescriptionrepeatingdata b inner join notesetctypes c on b.notesEtcTypeId = c.notesEtcTypeId
 where b.resourceId = pResourceId
 and b.repeatingDataType = 'Note' 
 and c.notesEtcName <> 'Preferred Citation note' 
 order by b.sequenceNumber;
end $

delimiter ;

-- procedure to get the bibliography for a resource
-- @param pResourceId bigint - the id of the resource
-- @result a resultset with the columns itemValue and repeatingDataType
delimiter $
create procedure findingAidItemBibliography(pResourceId bigInt(20))

begin
 select c.itemValue, b.repeatingDataType
 from archdescriptionrepeatingdata b inner join bibitems c on b.archDescriptionRepeatingDataId = c.parentId
 where b.resourceId = pResourceId
 and b.repeatingDataType = 'Bibliography'
 order by c.sequenceNumber;
end $

delimiter ;

-- procedure to get the personal names for the resource
-- @param pReosurceId bigint - the id of the resource
-- @result a resultset with the column personalName
delimiter $
create procedure findingAidItemPersonalName(pResourceId bigInt(20))

begin
 select creatorName(a.primaryNameId) as personalName
 from archdescriptionnames a
 where a.resourceId = pResourceId
 and a.nameLinkFunction = 'Subject'; 
end $

delimiter ;

-- procedure to get the subject terms for the resource
-- @param pResourceId bigint - the id of the resource
-- @result a resultset with the columns subjectTermType and subjectTerm
delimiter $
create procedure findingAidItemSubjects(pResourceId bigInt(20))

begin
 select b.subjectTermType, b.subjectTerm
 from archdescriptionsubjects a inner join subjects b on a.subjectId = b.subjectId
 where a.resourceId = pResourceId
 order by b.subjectTermType;
end $

delimiter ;

-- procedure to get the components for a resource
-- @param pResourceId bigint - the id of the resource
-- @result a resultset with the columns componentId, componentTitle, componentInstance, dateExpression, compOrder
-- The compOrder field is used to track the children of a component. The child component use the parent order and attach their order to it separating them with an _
-- 
delimiter $
create procedure findingAidItemComponents(pResourceId bigInt(20))

begin
 declare numComponents integer;
 declare maxSeq integer;
 declare p integer;
 
 -- temporary table for the resource components
 drop temporary table if exists resourceComps;
 create temporary table resourceComps (
  componentId bigint(20),
  componentTitle text COLLATE utf8_unicode_ci,
  componentInstance varchar(4000),
  dateExpression varchar(255) COLLATE utf8_unicode_ci,
  hasChild bit(1),
  processedFlag smallint,
  compOrder varchar(30)
 ) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
 
 drop temporary table if exists compIds;
 create temporary table compIds (
  componentId bigint(20),
  compOrder varchar(30)
 );
 
 if (exists (select 1 from resources where resourceId = pResourceId and findingAidStatus = 'Completed')) then
  -- compOrder is a varchar field and so the ordering would be based on the characterset and not on number ordering
  -- to get the proper ordering, create a number in format 10 raised to the power n where n is the maximum length of the sequence number
  -- this number would be added to the sequenceNumber of each component
  -- get the maximum length of the sequenceNumber used so far
  select max(sequenceNumber) into maxSeq from resourcesComponents;
  set p = pow(10, length(cast(maxSeq as char))); -- create the number
  
  -- get the components attached to the resource
  insert into resourceComps(componentId, componentTitle, hasChild, processedFlag, compOrder)
  select resourceComponentId, title, hasChild, 0, p + sequenceNumber
  from resourcesComponents
  where resourceId = pResourceId;
  
  -- for each component that has a child, get the components attached to it and then repeat until none of the new components have children
  select count(*) into numComponents from resourceComps where hasChild = 1;
  while (numComponents > 0) do
   update resourceComps set processedFlag = 1 where processedFlag = 0 and hasChild = 0;
   update resourceComps set processedFlag = -1 where processedFlag = 0 and hasChild = 1;
   
   -- store the componentId and ordering of the components that have children
   -- this is done because mysql 5.5 does not allow a temporary table to be used more than once in a query
   delete from compIds;
   insert into compIds(componentId, compOrder)
   select componentId, compOrder
   from resourceComps
   where processedFlag = -1;
   
   -- the order of the child is added to the number and is joined to the order of the parent and is separated by an underscore
   insert into resourceComps(componentId, componentTitle, hasChild, processedFlag, compOrder)
   select a.resourceComponentId, a.title, a.hasChild, 0, concat(b.compOrder, '_', p + a.sequenceNumber)
   from resourcesComponents a, compIds b
   where a.parentResourceComponentId = b.componentId;
   
   -- set these as processed
   update resourceComps set processedFlag = 1 where processedFlag = -1;
   -- check if there are any more components to get children for
   select count(*) into numComponents from resourceComps where hasChild = 1 and processedFlag = 0;
  end while;
  
  -- get the component instance
  update resourceComps a
  set componentInstance = (select componentContainer(a.componentId) from dual);
  
  -- get the date expression
  update resourceComps a
  set dateExpression = (select resourceComponentDate(a.componentId) from dual);
  
 end if;
 
 -- return data
 select componentId, componentTitle, componentInstance, dateExpression, compOrder
 from resourceComps
 order by compOrder;
end $

delimiter ;

-- procedure to get the notes for a component
-- @param pComponentId bigint - the component id 
-- @result a resultset containing the columns noteContent, notesEtcLabel
delimiter $
create procedure findingAidItemComponentNotes(pComponentId bigInt(20))

begin
 select b.noteContent, c.notesEtcLabel
 from archdescriptionrepeatingdata b inner join notesetctypes c on b.notesEtcTypeId = c.notesEtcTypeId
 where b.resourceComponentId = pComponentId
 and b.repeatingDataType = 'Note' 
 and c.notesEtcName <> 'Preferred Citation note' 
 order by b.sequenceNumber;
end $

delimiter ;

-- procedure to get the bibliography for a component
-- @param pComponentId bigint - the component id 
-- @result a resultset containing the columns itemValue, repeatingDataType
delimiter $
create procedure findingAidItemComponentBibliography(pComponentId bigInt(20))

begin
 select c.itemValue, b.repeatingDataType
 from archdescriptionrepeatingdata b inner join bibitems c on b.archDescriptionRepeatingDataId = c.parentId
 where b.resourceComponentId = pComponentId
 and b.repeatingDataType = 'Bibliography'
 order by c.sequenceNumber;
end $

delimiter ;

-- procedure to get the personal name for a component
-- @param pComponentId bigint - the component id 
-- @result a resultset containing the column personalName
delimiter $
create procedure findingAidItemComponentPersonalName(pComponentId bigInt(20))

begin
 select creatorName(a.primaryNameId) as personalName
 from archdescriptionnames a
 where a.resourceComponentId = pComponentId
 and a.nameLinkFunction = 'Subject'; 
end $

delimiter ;

-- procedure to get the subject terms for for a component
-- @param pComponentId bigint - the component id 
-- @result a resultset containing the columns subjectTermType, subjectTerm
delimiter $
create procedure findingAidItemComponentSubjects(pComponentId bigInt(20))

begin
 select b.subjectTermType, b.subjectTerm
 from archdescriptionsubjects a inner join subjects b on a.subjectId = b.subjectId
 where a.resourceComponentId = pComponentId
 order by b.subjectTermType;
end $

delimiter ;

