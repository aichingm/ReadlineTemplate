Readline Template
================

A php library which asks the user to feed you php application with certain data. 
The structure and questions are defined in a .xml file.

Documentation
========


Basic structure
---------------
    <Settings>
        <Text key="textA" prompt="Enter some text: "/>
        <Text key="textB" prompt="Enter an empty text: "/>
        
        <Integer key="intA" prompt="Enter a cool integer: "/>
        <Nubmer key="intB" prompt="Enter a cool number: "/>

    </Settings> 

As you can see the template is structured in xml format and has just one root element `Settings`. The user will be asked to answer everything in the `Settings` element, from top to bottom.
    
Important attributes
--------------------
There are 2 attributes which every element has:

1. `key` The `key` attribute is used to access its value and propably should be **uniqe** 
1. `prompt` The `prompt` attribute should give the user a hint what input is expected

Using a boolean element
-----------------

_Template Code_

    <Settings>
        <Boolean key="cool" prompt="Isn't this cool? "/>
    </Settings>

    
_Output_

    Isn't this cool?  [Y/n]: 

_Data_

    cool: true

__Note:__ The value of `cool` will be set to true for `Y`, `y` and `[empty]` but just `N` and `n` will result in false

Using a filename element
-----------------
_Template Code_

    <Settings>
        <File key="aExistingCoolFile" exists="true" prompt="Which existing file is cool? "/>
        <File key="aNotExistingCoolFile" exists="false" prompt="Which not existing file is cool? "/>
        <File key="aCoolFile" exists="" prompt="Which existing or not existing file is cool? "/>
        <File key="aCoolPhpFile" exists="true" extension=".php" prompt="Which php file is cool? "/>
    </Settings> 

_Attributes_

1. `exist` values are 
    1. `true` The file has to exist.
    1. `false` The file must not exist
    1. `[empty]` the file may or may not exist
1. `extension` The extension attribute will check if the filename ends with the given suffix in the case of the example `.php`
     
    
_Output_

    Which existing file is cool? LICENSE 
    Which not existing file is cool? LICENSE-one
    Which existing or not existing file is cool? .gitignore 
    Which php file is cool? src/ReadlineTemplate/ReadlineTemplate.php 

_Data_

    aExistingCoolFile:  LICENSE
    aNotExistingCoolFile:  LICENSE-one
    aCoolFile:  .gitignore
    aCoolPhpFile:  src/ReadlineTemplate/ReadlineTemplate.php

Using a hidden element
-----------------
A hidden element will not be visible to the user. It just adds a key value pair to the data.

_Template Code_

    <Settings>
        <Hidden key="cool" prompt="Isn't this cool? " default="This comes from nowhere!"/>
    </Settings>  

_Attributes_

1. `default` The `default` attributes value will be used as the key-value-pair's value.
1. `prompt` The `prompt` attribute will be ignored.

_Output_

    

_Data_

    cool: This comes from nowhere!

Using a list element
-----------------
A list element gives the user the oportunity to select on of many elements.

_Template Code_

    <Settings>
        <ListChoice key="coolCharacter" prompt="Which one is cooler? ">
            <Item value="A"/>
            <Item value="B"/>
        </ListChoice>
    </Settings> 

_Attributes_

1. `Item` The `Item` element is used to list all values in this list element
1. 1. `value` The `value` attribute will be used as the value for the key-value-pair.

_Output_

    Select one item by entering its number:
    1 A
    2 B
    Which one is cooler? 1

_Data_

    coolCharacter: A

__Note:__ The user will have to enter the number of the list entry to select it. The Numbers will start from 1.

Using a multi-list element
-----------------
A multi-list element gives the user the oportunity to select one or more elements.

_Template Code_

    <Settings>
        <MultiListChoice key="coolCharactersAre" prompt="Which one is cooler? "  seperator=",">
            <Item value="A"/>
            <Item value="B"/>
            <Item value="C"/>
            <Item value="D"/>
        </MultiListChoice>
        <MultiListChoice key="2coolCharactersAre" prompt="Which one is cooler? " min="2" max="2" seperator=",">
            <Item value="A"/>
            <Item value="B"/>
            <Item value="C"/>
            <Item value="D"/>
        </MultiListChoice>
    </Settings>

_Attributes_

1. `seperator` The user will use the value of the `seperator` attribute to seperate multiple selected values.
1. `min` The `min` attribute indicates the minimum number of selected values.
1. `max` The `max` attribute indicates the maximum number of selected values.
1. `Item` The `Item` element is used to list all values in this list element
1. 1. `value` The `value` attribute will be used as the value for the key-value-pair.

_Output_

    Select multiple items seperated with ',': 
    1 A
    2 B
    3 C
    4 D
    Which one is cooler? 1,2,3
    Select multiple items seperated with ',' the minimum amount of values is 2 the maximum amount of values is 2: 
    1 A
    2 B
    3 C
    4 D
    Which one is cooler? 4,2

_Data_

    coolCharactersAre:  array (
      0 => '1',
      1 => '2',
      2 => '3',
    )
    2coolCharactersAre:  array (
      0 => '4',
      1 => '2',
    )

__Note:__ The user will have to enter the number of the list entry to select it. The Numbers will start from 1.

Using a named-list element
-----------------
Like the multi-list element this element gives the user the oportunity to select one or more elements. The main difference is the the a multi list view is ordered and accessed by numbers starting from 1 and the named-lised element hase strings (names) which can be defined in the `Item` element.

_Template Code_

    <Settings>
        <NamedListChoice key="coolDatabase" prompt="Select one: ">
            <Item name="m" value="mariadb" text="MariaDB is Cool"/>
            <Item name="c" value="couchdb" text="CouchDB is cooler"/>
            <Item name="s" value="sqlite" text="Sqlite is da BOSS"/>
        </NamedListChoice>
        <NamedListChoice key="coolDatabases" prompt="Select multiple: " seperator="," min="2" max="2">
            <Item name="m" value="mariadb" text="MariaDB is Cool"/>
            <Item name="c" value="couchdb" text="CouchDB is cooler"/>
            <Item name="s" value="sqlite" text="Sqlite is da BOSS"/>
        </NamedListChoice>
    </Settings> 

_Attributes_

1. `seperator` The user will use the value of the `seperator` attribute to seperate multiple selected values.
1. `min` The `min` attribute indicates the minimum number of selected values.
1. `max` The `max` attribute indicates the maximum number of selected values.
1. `Item` The `Item` element is used to list all values in this list element
    1. `name` The `name` attribute is used as the text which the user has to enter to select an entry.
    1. `value`The `value` attribute will be used as the value for the key-value-pair.
    1. `text` The `Text` attribute will be displayed to the user.


_Output_

    Select one item by entering the text before the colon:
    m: MariaDB is Cool
    c: CouchDB is cooler
    s: Sqlite is da BOSS
    Select one: m
    Select multiple items by entering the text before the colon seperated with ',' the minimum amount of values is 2 the maximum amount of values is 2: 
    m: MariaDB is Cool
    c: CouchDB is cooler
    s: Sqlite is da BOSS
    Select multiple: m,c

_Data_

    coolDatabase:  array (
      0 => 'mariadb',
    )
    coolDatabases:  array (
      0 => 'mariadb',
      1 => 'couchdb',
    )


__Note:__ The user will have to enter the text before the colon to select the item.
