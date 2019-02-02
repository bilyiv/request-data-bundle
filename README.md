# Request data bundle

This bundle allows you to represent request data in a structured and useful way by creating request data classes.

Features:

* It represents query parameters
* It represents a request body and supports only `json` format for now
* It normalizes query parameters type
* It dispatches the event when request data is ready

## Installation

Run the following command using [Composer](http://packagist.org):

```sh
composer require bilyiv/request-data-bundle
```

## Usage

#### Create a request data class

```php
namespace App\RequestData;

class PaginationRequestData
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

#### Use this class in your controller

```php
namespace App\Controller;

class ExampleController extends Controller
{
    /**
     * @Route("/entities", name="index", methods={"GET"})
     */
    public function index(PaginationRequestData $data)
    {
        // Use request data object in your repository.
        $result = $this->getRepository(Entity::class)->paginate($data);
        
        // ...
    }
}
```

## License

This bundle is released under the MIT license. See the included [LICENSE](LICENSE) file for more information.
