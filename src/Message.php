<?php
	namespace Neon\Http;
	require_once 'MessageInterface.php';


	class Message implements \Psr\Http\Message\MessageInterface
	{
		private string $protocol_version;
		private array $header;

		public function __construct()
		{
			$this->header = getallheaders();

			foreach( $this->header as $key => $item )
			{
				$this->header[$key] = str_replace( ',', ', ', $this->header[$key] );
				$this->header[$key] = explode( ', ', $this->header[$key] );
			}

			$this->protocol_version = $_SERVER['SERVER_PROTOCOL'];
		}

		private function normalize_delemiter( string $header_value, $delemiter_from=',', $delemiter_to=', ' )
		{
			for( $i = 0; $i < strlen( $header_value); $i++ )
			{
				if( $header_value[$i] === ',' && $header_value[$i+1] !== ' ' ))
			}
		}

		/**
		 * @inheritDoc
		 */
		public function get_protocol_version()
		{
			return $this->protocol_version;
		}

		/**
		 * @inheritDoc
		 */
		public function with_protocol_version( $version )
		{

		}

		/**
		 * @inheritDoc
		 */
		public function get_headers()
		{
			// TODO: Implement get_headers() method.
			return $this->header;
		}

		/**
		 * @inheritDoc
		 */
		public function has_header( $name )
		{
			// TODO: Implement has_header() method.
		}

		/**
		 * @inheritDoc
		 */
		public function get_header( $name )
		{
			// TODO: Implement get_header() method.
		}

		/**
		 * @inheritDoc
		 */
		public function get_header_line( $name )
		{
			// TODO: Implement get_header_line() method.
		}

		/**
		 * @inheritDoc
		 */
		public function with_header( $name, $value )
		{
			// TODO: Implement with_header() method.
		}

		/**
		 * @inheritDoc
		 */
		public function with_added_header( $name, $value )
		{
			// TODO: Implement with_added_header() method.
		}

		/**
		 * @inheritDoc
		 */
		public function without_header( $name )
		{
			// TODO: Implement without_header() method.
		}

		/**
		 * @inheritDoc
		 */
		public function get_body()
		{
			// TODO: Implement get_body() method.
		}

		/**
		 * @inheritDoc
		 */
		public function with_body( StreamInterface|\Psr\Http\Message\StreamInterface $body )
		{
			// TODO: Implement with_body() method.
		}
	}