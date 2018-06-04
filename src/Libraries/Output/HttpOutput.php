<?php
declare(strict_types=1);

namespace JeremySells\AddressBook\Libraries\Output;

class HttpOutput implements OutputInterface
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
     *
     * @var string
     */
    private $content = '';

    //---GETTERS--------------------------------------------------------------

    /**
     *
     * @return string
     */
    public function getContent() : string
    {
        return $this->content;
    }

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

    //---SETTERS--------------------------------------------------------------

    /**
     *
     * @param string $content
     */
    public function setContent(string $content) : void
    {
        $this->content = $content;
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function setHeader(string $name, string $value) : void
    {
        $this->headers[$name] = $value;
    }

    /**
     * @param int $httpStatusCodeCode
     */
    public function setHttpStatusCodeCode(int $httpStatusCodeCode): void
    {
        $this->httpStatusCodeCode = $httpStatusCodeCode;
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

        //Output the content
        echo $this->content;
    }
}
