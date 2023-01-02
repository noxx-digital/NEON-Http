<?php
	namespace Neon\Http;

    use Neon\Http\Stream;
    use Neon\Util\Singleton;

    abstract class Message extends Singleton
	{
        /**
         * @var int
         */
        private int $chunk_length;

        /**
         * @var array
         */
        private array $header;

        /**
         * @var string|mixed
         */
        private string $protocol_version;

        /**
         * @var \Neon\Http\Stream
         */
        private Stream $body;

        protected function init()
        {
            $this->chunk_length = 1024;

            # set body as stream
            $this->body = new Stream();
            $this->write_chunks( file_get_contents( 'php://input' ), $this->body );

            # set server protocol
            $this->protocol_version = $_SERVER['SERVER_PROTOCOL'];
        }

        /**
         * @return string
         */
        public function get_protocol_version(): string
		{
			return $this->protocol_version;
		}

        /**
         * @return array
         */
		public function get_headers(): array
        {
			return $this->header;
		}

        /**
         * @param $name
         * @return bool
         */
		public function has_header( $name ): bool
		{
			return key_exists( $name, $this->header );
		}

        /**
         * @param string $name
         * @return string
         */
		public function get_header( string $name ): string
        {
			if( $this->has_header( $name ))
                return $this->header[$name];
            else
                return '';
		}

        /**
         * @param string $name
         * @return string
         */
		public function get_header_line( string $name ): string
		{
            if( $this->has_header( $name ))
			    return $name.': '.$this->header[$name];
            else
                return '';
		}

        /**
         * @param string $name
         * @param string $value
         * @return void
         */
        public function set_header( string $name, string $value ): void
		{
            $this->header[$name] = $value;
		}

        /**
         * @param array $header
         * @return void
         */
        public function set_headers( array $header=[] ): void
        {
            if( sizeof( $header ) > 0 )
                $this->header = $header;
        }

        /**
         * @param string $name
         * @return void
         */
        public function remove_header( string $name ): void
		{
            if( $this->has_header( $name ))
                unset( $this->header[$name] );
		}

        /**
         * @param string $str
         * @param \Neon\Http\Stream $stream
         * @return void
         */
        private function write_chunks( string $str, Stream $stream ): void
        {
            $chunked_body = str_split( $str, $this->chunk_length );
            foreach ( $chunked_body as $chunk )
                $stream->write( $chunk );
        }

        /**
         * @param string $data
         * @return void
         */
        public function write_body( string $data ): void
        {
            $this->write_chunks( $data, $this->body );
        }

        /**
         * @return void
         */
		public function print_body(): void
		{
            $this->body->rewind();
            $i = $this->chunk_length;
            do
            {
                if( $i > $this->body->get_size() )
                    $i = $this->body->get_size();

                echo $this->body->read( $i );
                $i += $this->chunk_length;
            }
            while ( $i < $this->body->get_size() );
		}
	}