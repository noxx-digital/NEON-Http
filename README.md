# NEON-Http


## Usage

```php
namespace Neon\Http;

require __DIR__ . '/../vendor/autoload.php';

$stream = new Stream( 'a+' );

$stream->write( 'Hello World!' );
$stream->rewind();
var_dump( $stream->tell() );
var_dump( $stream->read( $stream->get_size() ));
var_dump( $stream->tell() );

```