ReflectionDocBlock Documentation
===========================================

ReflectionDocBlock is a PHP library that provides a DocBlock parser fully compatible with the PHPDoc standard. It allows you to parse, interpret, and extract information from DocBlocks in your PHP code, enabling support for annotations and metadata extraction.

Key Features and Use Cases
--------------------------
- **Documentation Generation**: Used as a core component in tools like phpDocumentor to generate API documentation from your code's DocBlocks.
- **Type and Metadata Extraction**: Integrations and tools use this library to gather type information and other metadata, enabling advanced features such as static analysis, code introspection, and automated serialization.
- **Serializer Support**: Helps serializers and similar tools to interpret type information in array and object structures, making it easier to transform nested objects correctly.
- **DocBlock Reconstitution**: Not only can you read DocBlocks, but you can also reconstruct them. This is useful for adding, removing, or modifying tags in your codebase programmatically.
- **Standalone or Integrated**: Designed for standalone use, but also serves as a key component of the phpDocumentor suite.

Unique Advantages
-----------------
- **Simple, Intuitive API**: Focus on ease of use, so you can work with DocBlocks without needing to understand the complexities of parsing.
- **Widely Adopted**: Used by over 1000 packages on Packagist, making it a proven and reliable choice for PHP developers.
- **Actively Maintained**: Supports PHP 7.4 and 8+, and is maintained by the phpDocumentor team and contributors.

Quick Start Example
-------------------
Here's a minimal example of how to use ReflectionDocBlock in your project:

.. literalinclude:: examples/01-interpreting-a-simple-docblock.php
   :language: php
   :caption: examples/01-interpreting-a-simple-docblock.php

For more detailed usage and how-to guides, see the ``how-to/`` section.

.. toctree::
   :maxdepth: 2
   :hidden:

   installation
   how-to/index
   upgrade-to-v6
   contributing
