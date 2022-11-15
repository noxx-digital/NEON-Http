<?php
	namespace Psr\Http\Message;

	/**
	 * HTTP messages consist of requests from a client to a server and responses
	 * from a server to a client. This interface defines the methods common to
	 * each.
	 *
	 * Messages are considered immutable; all methods that might change state MUST
	 * be implemented such that they retain the internal state of the current
	 * message and return an instance that contains the changed state.
	 *
	 * @see http://www.ietf.org/rfc/rfc7230.txt
	 * @see http://www.ietf.org/rfc/rfc7231.txt
	 */
	interface MessageInterface
	{
		/**
		 * Retrieves the HTTP protocol version as a string.
		 *
		 * The string MUST contain only the HTTP version number (e.g., "1.1", "1.0").
		 *
		 * @return string HTTP protocol version.
		 */
		public function get_protocol_version();

		/**
		 * Return an instance with the specified HTTP protocol version.
		 *
		 * The version string MUST contain only the HTTP version number (e.g.,
		 * "1.1", "1.0").
		 *
		 * This method MUST be implemented in such a way as to retain the
		 * immutability of the message, and MUST return an instance that has the
		 * new protocol version.
		 *
		 * @param string $version HTTP protocol version
		 * @return static
		 */
		public function with_protocol_version($version);

		/**
		 * Retrieves all message header values.
		 *
		 * The keys represent the header name as it will be sent over the wire, and
		 * each value is an array of strings associated with the header.
		 *
		 * While header names are not case-sensitive, getHeaders() will preserve the
		 * exact case in which headers were originally specified.
		 *
		 * @return string[] Returns an associative array of the message's headers.
		 *     Each key MUST be a header name, and each value MUST be an array of
		 *     strings for that header.
		 */
		public function get_headers();

		/**
		 * Checks if a header exists by the given case-insensitive name.
		 *
		 * @param string $name Case-insensitive header field name.
		 * @return bool Returns true if any header names match the given header
		 *     name using a case-insensitive string comparison. Returns false if
		 *     no matching header name is found in the message.
		 */
		public function has_header($name);

		/**
		 * Retrieves a message header value by the given case-insensitive name.
		 *
		 * This method returns an array of all the header values of the given
		 * case-insensitive header name.
		 *
		 * If the header does not appear in the message, this method MUST return an
		 * empty array.
		 *
		 * @param string $name Case-insensitive header field name.
		 * @return string[] An array of string values as provided for the given
		 *    header. If the header does not appear in the message, this method MUST
		 *    return an empty array.
		 */
		public function get_header($name);

		/**
		 * Return an instance with the provided value replacing the specified header.
		 *
		 * While header names are case-insensitive, the casing of the header will
		 * be preserved by this function, and returned from getHeaders().
		 *
		 * This method MUST be implemented in such a way as to retain the
		 * immutability of the message, and MUST return an instance that has the
		 * new and/or updated header and value.
		 *
		 * @param string $name Case-insensitive header field name.
		 * @param string|string[] $value Header value(s).
		 * @return static
		 * @throws \InvalidArgumentException for invalid header names or values.
		 */
		public function with_header($name, $value);

		/**
		 * Return an instance with the specified header appended with the given value.
		 *
		 * Existing values for the specified header will be maintained. The new
		 * value(s) will be appended to the existing list. If the header did not
		 * exist previously, it will be added.
		 *
		 * This method MUST be implemented in such a way as to retain the
		 * immutability of the message, and MUST return an instance that has the
		 * new header and/or value.
		 *
		 * @param string $name Case-insensitive header field name to add.
		 * @param string|string[] $value Header value(s).
		 * @return static
		 * @throws \InvalidArgumentException for invalid header names.
		 * @throws \InvalidArgumentException for invalid header values.
		 */
		public function with_added_header($name, $value);

		/**
		 * Return an instance without the specified header.
		 *
		 * Header resolution MUST be done without case-sensitivity.
		 *
		 * This method MUST be implemented in such a way as to retain the
		 * immutability of the message, and MUST return an instance that removes
		 * the named header.
		 *
		 * @param string $name Case-insensitive header field name to remove.
		 * @return static
		 */
		public function without_header($name);

		/**
		 * Gets the body of the message.
		 *
		 * @return StreamInterface Returns the body as a stream.
		 */
		public function get_body();

		/**
		 * Return an instance with the specified message body.
		 *
		 * The body MUST be a StreamInterface object.
		 *
		 * This method MUST be implemented in such a way as to retain the
		 * immutability of the message, and MUST return a new instance that has the
		 * new body stream.
		 *
		 * @param StreamInterface $body Body.
		 * @return static
		 * @throws \InvalidArgumentException When the body is not valid.
		 */
		public function with_body(StreamInterface $body);
	}