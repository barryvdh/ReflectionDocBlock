Upgrade Guide to v6
===================

This guide helps you upgrade your project to ReflectionDocBlock v6. It covers breaking changes, removals, new features, and migration tips to ensure a smooth transition.

Supported PHP Versions
----------------------
- v6 requires PHP 7.4 or higher (PHP 8+ recommended).

Breaking Changes & Removals
---------------------------
- **Removal of `::create` static method for type-based tags**
  - The `create` static method has been removed from tag classes that represent type definitions, such as `@param` and `@return` tags. Most users will not be affected, as these methods are rarely used directly. The deprecation notice for these methods was present throughout v5.
  - **Migration:**
    - If you are instantiating these tag objects directly, use the tag factory or the recommended construction pattern instead.
    - Before:
      .. code-block:: php

         $tag = Param::create($body);
    - After:
      .. code-block:: php

         $factory = \phpDocumentor\Reflection\DocBlock\Tags\Factory\StandardTagFactory::createInstance();
         $tag = $factory->create('@param int $foo');

- **StandardTagFactory instantiation**
  - `StandardTagFactory` must now be created via `createInstance()`.
  - **Migration:**
    - Before:
      .. code-block:: php

         $factory = new StandardTagFactory();
    - After:
      .. code-block:: php

         $factory = StandardTagFactory::createInstance();

- **Removed methods**
  - `Method::getArguments` has been removed.
  - `Method::create` has been removed.
  - **Migration:**
    - Refactor code to use the new API for method arguments and creation.

TypeResolver Upgrade
-------------------
- **Generics Support**: The TypeResolver component now supports generics,
    which replaces the previous `Collection` type handling. This allows
    for more accurate and expressive type definitions, such as `MyClass<int, MyClass>` or `Collection<MyClass>`,
    and improves compatibility with modern PHPDoc standards.

- For more details and advanced migration scenarios, consult the `TypeResolver upgrade guide <https://docs.phpdoc.org/components/type-resolver/guides/upgrade-v1-to-v2.html#upgrade-to-version-2>`_
