Readline Template
=================

A php library which asks the user to feed your php application with certain data. 
The structure and questions are defined in a .xml file.

Requirements
============

*. php readline extension

Installation
============

Download it
-----------

    git clone https://github.com/aichingm/ReadlineTemplate

Require
-------

In your php file require the Autoload.php file. This file will cover the rest.

    require_once 'path/to/the/the/repository'.'/ReadlineTemplate/src/Autoload.php';

Using ReadlineTemplate in your application
==========================================

Loading the template
--------------------

Loading the template from a variable:

    $template = <<<EOF
    <Template>
        <Boolean key="cool" prompt="Isn't this cool? "/>
    </Template> 
    EOF;
    
    $rt = new \ReadlineTemplate\ReadlineTemplate($template);

Loading the template from a file:

    $rt = new \ReadlineTemplate\ReadlineTemplate(file_get_contents($template));

Loading the element readers
---------------------------


This is __important__ since no readers loaded by default! Load readers by adding them like: 

    $rt->addReader(new \ReadlineTemplate\reader\Boolean());
    $rt->addReader(new \ReadlineTemplate\reader\Text());
    $rt->addReader(new \MyProject\customReaders\MyCoolReader());
    //and so on

Or if you want to load all readers which are included in /src/ReadlineTemplate/reader use the  `loadDefaultReader` method of the `ReadlineTemplate` class:

    $rt->loadDefaultReader();


Validating the template
-----------------------
The directory src/ReadlineTemplate contains the file Template.xsd which is a valid xsd-schema. To validate your template use the `isValidTemplate` method of the `ReadlineTemplate` class:

The `ReadlineTemplate` class internally uses the `schemaValidate` method of the `DOMDocument` class.

    if($rt->isValidTemplate() != true) {
        echo "Failed to validate the template";
        exit();
    }

__Note__ If you are using custom elements in your template you have to provide a path to a schema where this elements are defined:

    $rt->isValidTemplate("path/to/my/schema.xsd") 

Run
---

Running the template means to start the process which asks the user for its input. Do this by calling `run` on your `ReadlineTemplate` instance.

    $collectedData = $rt->run();

Obtaining the collected data
----------------------------

The `run` method returns an array with four items in it, namely "data", "extra" 0 and 1.
Have a look at the _Exclude_ section of the documentation to read about what is in the **data** section and what is in the **extra** section.

Use

    list($data, $extra) = $rt->run();

or

    $array = $rt->run();
    $data = $array["data"];
    $extra = $array["extra"];

to access the collected data.

Example
-------

_Template_

    <Template>
        <Boolean key="canIAsk" prompt="Can I ask you some thing? " exclude="from-data"/>
        <Text key="name" prompt="What's your name? " depends="canIAsk" depends-equals="true"/>
    </Template> 

_Input_

    Can I ask you some thing?  [Y/n]: y
    What's your name? Mario

_var_dump() of $rt->run()_: 

    array(4) {
      [0]=>
      &array(1) {
        ["name"]=>
        string(5) "Mario"
      }
      [1]=>
      &array(1) {
        ["canIAsk"]=>
        bool(true)
      }
      ["data"]=>
      &array(1) {
        ["name"]=>
        string(5) "Mario"
      }
      ["extra"]=>
      &array(1) {
        ["canIAsk"]=>
        bool(true)
      }
    }


Examples
========

Examples for all elements can be found in the examples directory.

Documentation
=============


Basic structure
---------------
    <Template>
        <Text key="textA" prompt="Enter some text: "/>
        <Text key="textB" prompt="Enter an empty text: "/>
        
        <Integer key="intA" prompt="Enter a cool integer: "/>
        <Nubmer key="intB" prompt="Enter a cool number: "/>

    </Template> 

As you can see the template is structured in xml format and has just one root element `Template`. The user will be asked to answer everything in the `Template` element, from top to bottom.
    
Important attributes
--------------------
There are 2 attributes which every element has to have:

1. `key` The `key` attribute is used to access its value and probably should be **unique** 
1. `prompt` The `prompt` attribute should give the user a hint what input is expected

Besides the 2 mandatory `key` and `prompt` attributes there are 3 optional attributes which every element supports 

1. `depends` The `depends` attribute tells the program that this question should only be asked if the value of `depends` is a existing key in the data. 
1. `depends-equals` Use the `depends-equals` attribute to specify which exact value the key should have to which the `depends` field refers.
1. `exclude` Use the `exclude="from-data"` attribute-value-pair to exclude a key from the data section in the result array which gets returned from the `run` method of the `ReadlineTemplate` class.


Using a boolean element
-----------------

_Template Code_

    <Template>
        <Boolean key="cool" prompt="Isn't this cool? "/>
    </Template>

    
_Input_

    Isn't this cool?  [Y/n]: 

_Data_

    cool: true

__Note:__ The value of `cool` will be set to true for `Y`, `y` and `[empty]` but just `N` and `n` will result in false

Using an integer element
------------------------
Use this element to read signed integers.
_Template Code_

    <Template>
        <Integer key="coolNumber" prompt="What is a cool number? "/>
        <Integer key="coolNumberbetween" prompt="What is a cool number between 5 and 10? " min="5" max="10"/>
    </Template>
    
_Attributes_

1. `min` The `min` attribute indicates the minimum value.
1. `max` The `max` attribute indicates the maximum value.
1. `default` The `default` attribute specifies the value which is used if the user enters an empty value.


_Input_

    What is a cool number? 23
    What is a cool number between 5 and 10? 9

_Data_

    coolNumber:  23
    coolNumberBetween:  9

__Note:__ For the second question 5, 6, 7, 8, 9 and 10 are valid values.

Using a number element
------------------------
Use this element to read signed double values. **Keep the floating point precision in mind!**

_Template Code_

    <Template>
        <Number key="coolNumber" prompt="What is a cool number? "/>
        <Number key="coolNumberBetween" prompt="What is a cool number between 5 and 10? " min="5" max="10"/>
    </Template>
    
_Attributes_

1. `min` The `min` attribute indicates the minimum value.
1. `max` The `max` attribute indicates the maximum value.
1. `default` The `default` attribute specifies the value which is used if the user enters an empty value.


_Input_

    What is a cool number? 23
    What is a cool number between 5 and 10? 9

_Data_

    coolNumber:  23
    coolNumberBetween:  9

__Note:__ For the second question 5, 6, 7, 8, 9 and 10 are valid values. Keep in mind that the input will be casted to a double value so it might happen that 10.00000000000001 also represents a valid input.

Using a text element
------------------------
Use this element to read text values.

_Template Code_

    <Template>
        <Text key="coolName" prompt="What is a cool name? "/>
    </Template> 

_Attributes_

1. `default` The `default` attribute specifies the value which is used if the user enters an empty value.

_Input_



_Data_

    coolName: Mario

Using a regex element
------------------------
Use this element to read text values with a certain pattern.

_Template Code_

    <Template>
        <Regex key="fancyString" prompt="What is your locale? " pattern="[A-Z]{2}_[a-z]{2}"/>
        <Regex key="superFancyString" prompt="Come on what is your locale? " pattern="~^[A-Z]{2}_[a-z]{2}$~" raw="true"/>
    </Template>  

_Attributes_

1. `pattern` The `pattern` attribute specifies the regular expressions pattern.
1. `raw` The `raw` attribute used with the value `true` specifies that you add the delimiters, the begin symbol and the end symbol your self. If not used your pattern will be encapsulated by `~^` and `$~`. This is useful if you want to add your own regular expression flags.

_Input_

    What is your locale? DE_at
    Come on what is your locale? EN_us

_Data_

    fancyString:  DE_at
    superFancyString:  EN_us

Using a filename element
------------------------
Use this element to read filenames.
_Template Code_

    <Template>
        <File key="aExistingCoolFile" exists="true" prompt="Which existing file is cool? "/>
        <File key="aNotExistingCoolFile" exists="false" prompt="Which not existing file is cool? "/>
        <File key="aCoolFile" exists="" prompt="Which existing or not existing file is cool? "/>
        <File key="aCoolPhpFile" exists="true" extension=".php" prompt="Which php file is cool? "/>
    </Template> 

_Attributes_

1. `exist` values are:
    1. `true` The file has to exist.
    1. `false` The file must not exist
    1. `[empty]` the file may or may not exist
1. `extension` The extension attribute will check if the filename ends with the given suffix in the case of the example `.php`

         
_Input_

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
A hidden element will not be visible to the user. It just adds a key-value-pair to the data.

_Template Code_

    <Template>
        <Hidden key="cool" prompt="Isn't this cool? " default="This comes from nowhere!"/>
    </Template>  

_Attributes_

1. `default` The `default` attributes value will be used as the key-value-pair's value.
1. `prompt` The `prompt` attribute will be ignored.

_Input_

    

_Data_

    cool: This comes from nowhere!

Using a list element
-----------------
A list element gives the user the opportunity to select on of many elements.

_Template Code_

    <Template>
        <ListChoice key="coolCharacter" prompt="Which one is cooler? ">
            <Item value="A"/>
            <Item value="B"/>
        </ListChoice>
    </Template> 

_Attributes_

1. `Item` The `Item` element is used to list all values in this list element
1. 1. `value` The `value` attribute will be used as the value for the key-value-pair.

_Input_

    Select one item by entering its number:
    1 A
    2 B
    Which one is cooler? 1

_Data_

    coolCharacter: A

__Note:__ The user will have to enter the number of the list entry to select it. The Numbers will start from 1.

Using a multi-list element
-----------------
A multi-list element gives the user the opportunity to select one or more elements.

_Template Code_

    <Template>
        <MultiListChoice key="coolCharactersAre" prompt="Which one is cooler? "  separator=",">
            <Item value="A"/>
            <Item value="B"/>
            <Item value="C"/>
            <Item value="D"/>
        </MultiListChoice>
        <MultiListChoice key="2coolCharactersAre" prompt="Which one is cooler? " min="2" max="2" separator=",">
            <Item value="A"/>
            <Item value="B"/>
            <Item value="C"/>
            <Item value="D"/>
        </MultiListChoice>
    </Template>

_Attributes_

1. `separator` The user will use the value of the `separator` attribute to separate multiple selected values.
1. `min` The `min` attribute indicates the minimum number of selected values.
1. `max` The `max` attribute indicates the maximum number of selected values.
1. `Item` The `Item` element is used to list all values in this list element
1. 1. `value` The `value` attribute will be used as the value for the key-value-pair.

_Input_

    Select multiple items separated with ',': 
    1 A
    2 B
    3 C
    4 D
    Which one is cooler? 1,2,3
    Select multiple items separated with ',' the minimum amount of values is 2 the maximum amount of values is 2: 
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
Like the multi-list element this element gives the user the opportunity to select one or more elements. The main difference is the the a multi list view is ordered and accessed by numbers starting from 1 and the named-lised element hase strings (names) which can be defined in the `Item` element.

_Template Code_

    <Template>
        <NamedListChoice key="coolDatabase" prompt="Select one: ">
            <Item name="m" value="mariadb" text="MariaDB is Cool"/>
            <Item name="c" value="couchdb" text="CouchDB is cooler"/>
            <Item name="s" value="sqlite" text="Sqlite is da BOSS"/>
        </NamedListChoice>
        <NamedListChoice key="coolDatabases" prompt="Select multiple: " separator="," min="2" max="2">
            <Item name="m" value="mariadb" text="MariaDB is Cool"/>
            <Item name="c" value="couchdb" text="CouchDB is cooler"/>
            <Item name="s" value="sqlite" text="Sqlite is da BOSS"/>
        </NamedListChoice>
    </Template> 

_Attributes_

1. `separator` The user will use the value of the `separator` attribute to separate multiple selected values.
1. `min` The `min` attribute indicates the minimum number of selected values.
1. `max` The `max` attribute indicates the maximum number of selected values.
1. `Item` The `Item` element is used to list all values in this list element
    1. `name` The `name` attribute is used as the text which the user has to enter to select an entry.
    1. `value`The `value` attribute will be used as the value for the key-value-pair.
    1. `text` The `Text` attribute will be displayed to the user.


_Input_

    Select one item by entering the text before the colon:
    m: MariaDB is Cool
    c: CouchDB is cooler
    s: Sqlite is the BOSS
    Select one: m
    Select multiple items by entering the text before the colon separated with ',' the minimum amount of values is 2 the maximum amount of values is 2: 
    m: MariaDB is Cool
    c: CouchDB is cooler
    s: Sqlite is the BOSS
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

Depends
=======
An element can depend on the existence of an other element or its value.

    <Template>
        <Boolean key="canIAsk" prompt="Can I ask you some thing? "/>
        <Text key="name" prompt="What's your name? " depends="canIAsk" depends-equals="true"/>
        <Boolean key="canIAsk1" prompt="Hey Mario can I ask you one more thing? " depends="name" depends-equals="Mario"/>
    </Template> 

The example above has three elements. The first element will ask the user to answer a polar question. If the user answers positively the program will continue and present the next element. If the user answers the second question with `Mario` the program will present the last question.

Example with positive `depends` conditions
-----------------------------------------

_Input_

    Can I ask you some thing?  [Y/n]: y
    What's your name? Mario
    Hey Mario can I ask you one more thing?  [Y/n]: y

_Data_

    canIAsk: true
    name:  Mario
    canIAsk1: true

Example with negative `depends` conditions
-----------------------------------------

_Input_

    Can I ask you some thing?  [Y/n]: n

_Data_

    canIAsk: false
   
Exclude
=======

The data which will be returned from the `run` method is contained in an array with two sections **data** and **extra**. To exclude a key-value-pair from the **data** section and move it to the **extra** section use the `exclude` attribute with the `from-data` value. 

_Example_

    <Template>
        <Text key="password" prompt="I swear this will not end up in the collected data! What is your password? " exclude="from-data"/>
        <Text key="name" prompt="What's your name? (This will end up in data) " />
    </Template>

_Input_

    I swear this will not end up in the collected data! What is your password? password1
    What's your name? (This will end up in data) Mario

_Data & Extra_

    data:
        name:  Mario
    extra:
        password:  password1
    
    
_Note_ Elements which are excluded from the **data** section by using the `exclude` attribute and the `from-data` value **are** available in the `depends` and `depends-equals` attributes.



