# Request data bundle

This bundle allows you to represent already validated request data in a structured way by creating specific classes.

Some features: query parameters type normalization, `json` request body deserialization, data validation.

## Getting started

### Create a request data class
```php
namespace App\RequestData;

class UserSearchRequestData
{
    public const DEFAULT_LIMIT = 10;

    /**
     * @Assert\Length(min=3)
     *
     * @var null|string
     */
    public $name;

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

### Use it in your controller

```php
namespace App\Controller;

class UserController extends Controller
{
    /**
     * @Route("/users", name="users-search", methods={"GET"})
     */
    public function search(UserSearchRequestData $data)
    {
        // here you have already validated and fully safe request data.
        echo $data->name, $data->offset, $data->limit;
        
        $users = $this->getRepository(User::class)->search($data);
        
        // ...
    }
}
```
