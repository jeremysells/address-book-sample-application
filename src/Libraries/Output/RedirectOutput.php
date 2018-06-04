<?php
declare(strict_types=1);

namespace JeremySells\AddressBook\Libraries\Output;

class RedirectOutput implements OutputInterface
{
    /**
     *
     * @var string[]
     */
    private $headers = [];

    /**
     *
     * @var int
     */
    private $httpStatusCodeCode = 200; //Ok

    /**
     * Constructor.
     *
     * @param string $location
     * @param int $statusCode
     */
    public function __construct(string $location, int $statusCode = 302)
    {
        $this->headers['Location'] = $location;
        $this->httpStatusCodeCode = $statusCode;
    }

    //---GETTERS--------------------------------------------------------------

    /**
     *
     * @return string[]
     */
    public function getHeaders() : array
    {
        return $this->headers;
    }

    /**
     *
     * @return int
     */
    public function getHttpStatusCodeCode() : int
    {
        return $this->httpStatusCodeCode;
    }

    /**
     * Gets the redirect location
     * @return string
     */
    public function getLocation() : string
    {
        return $this->headers['Location'];
    }

    //---LOGIC----------------------------------------------------------------

    /**
     * @codeCoverageIgnore
     * {@inheritdoc}
     */
    public function output() : void
    {
        //Set the http response code
        http_response_code($this->httpStatusCodeCode);

        //Send headers
        foreach ($this->headers as $key => $value) {
            header("{$key}: {$value}");
        }
    }
}
