Feature: describe
    In order to get the extension of a file
    For a given file
    I need to be able to pass a file and get back the extension

Scenario: Get the extension of a file
    Given I have a file called "text.txt"
    When I describe the file
    Then I should get an array containing an extension of: "txt"