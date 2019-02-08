# Request data bundle

This bundle allows you to represent request data in a structured and useful way by creating request data classes.

**Features**:

* Detecting how to extract data depends on request method and `Content-Type` header.
* Representing and normalizing query parameters for the `GET` request method.
* Representing `form`, `json` request body for the `POST`, `PUT`, `PATCH` request methods.
* Dispatching the finish event when request data is ready.

## Installation

Run the following command using [Composer](http://packagist.org):

```sh
composer require bilyiv/request-data-bundle
```

## Configuration

The default configuration is the following:

```yaml
request_data:
    prefix: App\RequestData
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
