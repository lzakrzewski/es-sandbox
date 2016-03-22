Feature: Basket

  Scenario: Add product to basket
    Given My basket is empty
     When I add product with id "6a45032e-738a-48b7-893d-ebdc60d0c3b7" and name "Teapot"
     Then I should be notified that product was added to basket
      And My basket should contain 1 products

  Scenario: Remove product from basket
    Given My basket contains products:
        | productId                            | name   |
        | 6a45032e-738a-48b7-893d-ebdc60d0c3b7 | Teapot |
     When I remove product with "6a45032e-738a-48b7-893d-ebdc60d0c3b7"
     Then I should be notified that product was removed from basket
      And My basket should contain 0 products

  Scenario: View basket products
    Given My basket contains products:
        | productId                            | name   |
        | 6a45032e-738a-48b7-893d-ebdc60d0c3b7 | Teapot |
        | 9fec03ef-42de-4d2f-8905-b25a71b50e00 | Iron   |
     When I view my basket
     Then I should see:
        | productId                            | name   |
        | 6a45032e-738a-48b7-893d-ebdc60d0c3b7 | Teapot |
        | 9fec03ef-42de-4d2f-8905-b25a71b50e00 | Iron   |