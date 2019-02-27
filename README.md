# Request data bundle

This bundle allows you to represent request data in a structured and useful way by creating request data classes.

**Features**:

* Detecting how to extract data depends on request method and `Content-Type` header.
* Representing and normalizing query parameters for the `GET` request method.
* Representing `form`, `json`, `xml` request body for the `POST`, `PUT`, `PATCH` request methods.
* Defining supported formats and throwing exception if the request format is unsupported.
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

class PostRequestData implements FormatSupportableInterface
{
    public const DEFAULT_AUTHOR = 'none';

    /**
     * @var string
     */
    public $title;

    /**
     * @var string
     */
    public $author = self::DEFAULT_AUTHOR;

    /**
     * {@inheritdoc}
     */
    public static function getSupportedFormats(): array
    {
        return [Formats::FORM, Formats::JSON, Formats::XML];
    }
}
```

#### Use it in your controller

```php
namespace App\Controller;

class PostController extends AbstractController
{
    /**
     * @Route("/", name="action")
     */
    public function action(PostRequestData $data)
    {
       return new JsonResponse($data);
    }
}
```

#### Make requests

All the following requests will return the same json response:

```json
{
    "title": "Hamlet",
    "author": "William Shakespeare"
}
```

`GET` request:

```bash
curl -X GET 'https://example.com?title=Hamlet&author=William+Shakespeare'
```

`POST` form request:

```bash
curl -X POST 'https://example.com' \
     -H 'Content-Type: application/x-www-form-urlencoded' \
     -d 'title=Hamlet&author=William+Shakespeare'
```

`POST` json request:

```bash
curl -X POST 'https://example.com' \
     -H 'Content-Type: application/json' \
     -d '{"title":"Hamlet","author":"William Shakespeare"}'
```

`POST` xml request:

```bash
curl -X POST 'https://example.com' \
     -H 'Content-Type: application/xml' \
     -d '<post><title>Hamlet</title><author>William Shakespeare</author></post>'
```

`POST` csv request throws an exception because of unsupported format:

```bash
curl -X POST 'https://example.com' \
     -H 'Content-Type: application/csv' \
     -d 'Hamlet,William Shakespeare'
```

## License

This bundle is released under the MIT license. See the included [LICENSE](LICENSE) file for more information.
