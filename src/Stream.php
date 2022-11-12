<?php

namespace Neon\Http;

use RuntimeException;

class Stream
{
    protected $stream;

    /**
     * @throws ArgumentException
     */
    public final function __construct(
        private readonly string $mode
    )
    {
        if(
            $this->mode !== 'r' &&
            $this->mode !== 'r+' &&
            $this->mode !== 'w' &&
            $this->mode !== 'w+' &&
            $this->mode !== 'a' &&
            $this->mode !== 'a+' &&
            $this->mode !== 'x' &&
            $this->mode !== 'x+' &&
            $this->mode !== 'c' &&
            $this->mode !== 'c+'
        )
        {
            throw new ArgumentException('Not existing mode Provided.');
        }

        $this->stream = fopen( 'php://temp', $this->mode );
    }

    /**
     * Reads all data from the stream into a string, from the beginning to end.
     *
     * This method MUST attempt to seek to the beginning of the stream before
     * reading data and read the stream until the end is reached.
     *
     * Warning: This could attempt to load a large amount of data into memory.
     *
     * This method MUST NOT raise an exception in order to conform with PHP's
     * string casting operations.
     *
     * @see http://php.net/manual/en/language.oop5.magic.php#object.tostring
     * @return string
     */
    public final function __toString(): string
    {
        $this->rewind();
        return $this->read( $this->get_size() );
    }

    /**
     * Closes the stream and any underlying resources.
     * Closes the stream and any underlying resources.
     *
     * @return void
     */
    public final function close(): void
    {
       fclose( $this->stream );
    }

    /**
     * Separates any underlying resources from the stream.
     *
     * After the stream has been detached, the stream is in an unusable state.
     *
     * @return resource|null Underlying PHP stream, if any
     */
    protected function detach()
    {
        return NULL;
    }

    /**
     * Get the size of the stream if known.
     *
     * @return int|null Returns the size in bytes if known, or null if unknown.
     */
    public final function get_size(): int|null
    {
        return fstat( $this->stream )['size'];
    }

    /**
     * Returns the current position of the file read/write pointer
     *
     * @return int Position of the file pointer
     * @throws \RuntimeException on error.
     */
    public final function tell(): int
    {
        if(( $pos = ftell( $this->stream )) === FALSE )
            throw new RuntimeException('');
        return $pos;
    }

    /**
     * Returns true if the stream is at the end of the stream.
     *
     * @return bool
     */
    public final function eof(): bool
    {
        return feof( $this->stream );
    }

    /**
     * Returns whether or not the stream is seekable.
     *
     * @return bool
     */
    public final function is_seekable(): bool
    {
        return stream_get_meta_data( $this->stream )['seekable'];
    }

    /**
     * Seek to a position in the stream.
     *
     * @link http://www.php.net/manual/en/function.fseek.php
     * @param int $offset Stream offset
     * @param int $whence Specifies how the cursor position will be calculated
     *     based on the seek offset. Valid values are identical to the built-in
     *     PHP $whence values for `fseek()`.  SEEK_SET: Set position equal to
     *     offset bytes SEEK_CUR: Set position to current location plus offset
     *     SEEK_END: Set position to end-of-stream plus offset.
     * @throws \RuntimeException on failure.
     */
    public final function seek( int $offset, int $whence=SEEK_SET ): void
    {
        if( fseek( $this->stream, $offset, $whence ) === -1 )
            throw new RuntimeException('Stream is not seekable.');
    }

    /**
     * Seek to the beginning of the stream.
     *
     * If the stream is not seekable, this method will raise an exception;
     * otherwise, it will perform a seek(0).
     *
     * @see seek()
     * @link http://www.php.net/manual/en/function.fseek.php
     * @throws \RuntimeException on failure.
     */
    public final function rewind(): void
    {
       if( !rewind( $this->stream ))
           throw new RuntimeException('Stream is not seekable.');
    }

    /**
     * Returns whether or not the stream is writable.
     *
     * @return bool
     */
    public final function is_writable(): bool
    {
        if(
            $this->mode === 'r+' ||
            $this->mode === 'w' ||
            $this->mode === 'w+' ||
            $this->mode === 'a' ||
            $this->mode === 'a+' ||
            $this->mode === 'x' ||
            $this->mode === 'x+' ||
            $this->mode === 'c' ||
            $this->mode === 'c+'
        )
            return TRUE;
        else
            return FALSE;
    }

    /**
     * Write data to the stream.
     *
     * @param string $string The string that is to be written.
     * @return int Returns the number of bytes written to the stream.
     * @throws \RuntimeException on failure.
     */
    public final function write( string $string ): int
    {
        if(( $write = fwrite( $this->stream, $string, strlen( $string ))) === FALSE )
            throw new RuntimeException('Cannot write to stream.');

        return $write;
    }

    /**
     * Returns whether or not the stream is readable.
     *
     * @return bool
     */
    public final function is_readable(): bool
    {
        if(
            $this->mode === 'r' ||
            $this->mode === 'r+' ||
            $this->mode === 'w+' ||
            $this->mode === 'a+' ||
            $this->mode === 'x+' ||
            $this->mode === 'c+'
        )
            return TRUE;
        else
            return FALSE;
    }

    /**
     * Read data from the stream.
     *
     * @param int $length Read up to $length bytes from the object and return
     *     them. Fewer than $length bytes may be returned if underlying stream
     *     call returns fewer bytes.
     * @return string Returns the data read from the stream, or an empty string
     *     if no bytes are available.
     * @throws \RuntimeException if an error occurs.
     */
    public final function read( int $length ): string
    {
        if(( $read = fread( $this->stream, $length )) === FALSE )
            throw new RuntimeException('Cannot read from stream.');

        return $read;
    }

    /**
     * Returns the remaining contents in a string
     *
     * @return string
     * @throws \RuntimeException if unable to read or an error occurs while
     *     reading.
     */
    public final function get_contents(): string
    {
        return $this->read( $this->get_size() - $this->tell() );
    }

    /**
     * Get stream metadata as an associative array or retrieve a specific key.
     *
     * The keys returned are identical to the keys returned from PHP's
     * stream_get_meta_data() function.
     *
     * @link http://php.net/manual/en/function.stream-get-meta-data.php
     * @param string|null $key Specific metadata to retrieve.
     * @return array|mixed|null Returns an associative array if no key is
     *     provided. Returns a specific key value if a key is provided and the
     *     value is found, or null if the key is not found.
     */
    public final function get_metadata( string $key=NULL )
    {
        $meta_data = stream_get_meta_data( $this->stream );
        if( $key === NULL )
            return $meta_data;
        elseif( key_exists( $key, $meta_data ) )
            return $meta_data[$key];
        else
            return NULL;
    }
}