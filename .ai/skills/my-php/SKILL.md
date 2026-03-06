---
name: my-php
description: opinionated PHP code. Activates when generating PHP code
---

# My PHP

## Array as function argument, class method argument or class property

Because PHP doesn't support generics, use the following to add an array type argument to a function or a class method

- Create a class with the suffix Collection in the same namespace as the function or class.
- The class has a private property of the array type called collection. Only in these classes a property can have type array.
- The constructor only accepts a single type of arguments, and uses the spread operator to reduce them to an array.
- The array of arguments is added to the collection property.
- The class needs a toArray method that exposes the collection property.
- When the function or the class method argument needs filtering or manipulation of the array, add the relevant methods to the class.
- Create tests for all the methods of the class.

