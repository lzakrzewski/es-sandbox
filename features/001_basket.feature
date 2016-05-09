Feature: Basket

  Scenario: Pick up basket
    Given I don't have basket
     When I pick up basket
     Then I should be notified that was picked up
      And My basket should contain 0 products

  Scenario: Add product to basket
    Given I have basket picked up
     When I add product with id "6a45032e-738a-48b7-893d-ebdc60d0c3b7" and name "Teapot" to my basket
     Then I should be notified that product was added to basket
      And My basket should contain 1 products

  Scenario: Remove product from basket
    Given I have basket picked up
      And My basket contains products:
        | productId                            | name   |
        | 6a45032e-738a-48b7-893d-ebdc60d0c3b7 | Teapot |
     When I remove product with id "6a45032e-738a-48b7-893d-ebdc60d0c3b7" from my basket
     Then I should be notified that product was removed from basket
      And My basket should contain 0 products

  Scenario: Remove not existing product from basket
    Given I have basket picked up
     When I remove product with id "6a45032e-738a-48b7-893d-ebdc60d0c3b7" from my basket
     Then I should be notified that product does not exists
      And My basket should contain 0 products