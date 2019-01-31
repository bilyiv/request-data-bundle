# Request data bundle

This bundle allows you to represent request data in a structured and useful way by creating request data classes.

It supports query parameters type normalization, request body deserialization.

## Getting started

### Create a request data class

```php
namespace App\RequestData;

class SearchRequestData
{
    public const DEFAULT_LIMIT = 10;

    /**
     * @var null|int
     */
    public $offset;

    /**
     * @var null|int
     */
    public $limit = self::DEFAULT_LIMIT;
}
```

### Use this class in your controller

```php
namespace App\Controller;

class ExampleController extends Controller
{
    /**
     * @Route("/search", name="search", methods={"GET"})
     */
    public function search(SearchRequestData $data)
    {
        // Use request data object in your repository.
        $result = $this->getRepository(Entity::class)->search($data);
        
        // ...
    }
}
```
