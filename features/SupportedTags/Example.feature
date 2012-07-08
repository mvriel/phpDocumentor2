Feature: Support @example tag
  In order to include elaborate examples with my generated documentation
  as documentation generating user
  I need to provide a tag '@example' that embeds examples into my structure file
    and shows them in a template

  Background:
    Given I am in the temporary directory
    And a folder "phpDocumentorExampleTest/examples"
    And a folder "phpDocumentorExampleTest/subfolder/examples"
    And a file "phpDocumentorExampleTest/DummyForProjectRoot.php" with
    """
    <?php
    /** @example example1.php */
    function RootExampleTest() {}
    """
    And a file "phpDocumentorExampleTest/subfolder/SubFolderTest.php" with
    """
    <?php
    /** @example example2.php */
    function SubFolderExampleTest() {}
    """
    And a file "phpDocumentorExampleTest/subfolder/ProjectRootTest.php" with
    """
    <?php
    /** @example example1.php */
    function RootExampleFromSubFolderTest() {}
    """
    And a file "phpDocumentorExampleTest/examples/example1.php" with
    """
    <?php
    echo 'example1';
    """
    And a file "phpDocumentorExampleTest/subfolder/examples/example2.php" with
    """
    <?php
    echo 'example2';
    """

  Scenario: Single-line inline example code is embedded in the AST
    Given I am in the phpDocumentor root directory
    When I run phpDocumentor with:
    """
    <?php
    /** @example a() */
    function a() {}
    """
    Then my AST should contain the tag "example" for function "\a" with
    """
    a()
    """

  Scenario: Multi-line inline example code is embedded in the AST
    Given I am in the phpDocumentor root directory
    When I run phpDocumentor with:
    """
    <?php
    /**
     * @example
     *   $a = 1 + 1;
     *   $a *= 2;
     *   echo $a;
     */
    function a() {}
    """
    Then my AST should contain the tag "example" for function "\a" with
    """
      $a = 1 + 1;
      $a *= 2;
      echo $a;
    """

  Scenario: External file from Structural Element's `examples` subdirectory is
    embedded in the AST
    Given I am in the phpDocumentor root directory
    When I run phpDocumentor against the directory "{tmp}/phpDocumentorExampleTest"
    Then my AST should contain the tag "example" for function "\SubFolderExampleTest" with
    """
    <?php
    echo 'example2';
    """

  Scenario: External file from Project Root's `examples` subdirectory is
    embedded in the AST
    Given I am in the phpDocumentor root directory
    When I run phpDocumentor against the directory "{tmp}/phpDocumentorExampleTest"
    Then my AST should contain the tag "example" for function "\RootExampleFromSubFolderTest" with
    """
    <?php
    echo 'example1';
    """

  Scenario: External file from Configuration's `examples` subdirectory is
    embedded in the AST

  Scenario: External file from Command Line option's `examples` subdirectory is
    embedded in the AST

  Scenario: External file embedded in the AST using an absolute path

  Scenario: External file embedded in the AST using a scheme and relative path
    to Structural Element's `examples` subdirectory

  Scenario: External file embedded in the AST using a scheme and absolute path
    to Structural Element's `examples` subdirectory

  Scenario: Error is thrown if external file has an unsupported extension

  Scenario: Error is thrown if external file could not be found

