# Request data bundle

This bundle allows you to represent already validated request data in a structured way by creating specific classes.

Some features: query parameters type normalization, `json` request body deserialization, data validation.

## Getting started

### Create a request data class

```php
namespace App\RequestData;

class SearchRequestData
{
    public const DEFAULT_LIMIT = 10;

    /**
     * @Assert\GreaterThanOrEqual(0)
     *
     * @var null|int
     */
    public $offset;

    /**
     * @Assert\LessThanOrEqual(100)
     *
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
        // Pass the data with already validated offset, limit to repository method. 
        $users = $this->getRepository(User::class)->search($data);
        
        // ...
    }
}
```
