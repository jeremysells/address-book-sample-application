<?php
declare(strict_types=1);

namespace JeremySells\AddressBook\Controllers;

use JeremySells\AddressBook\Libraries\Output\HttpOutput;
use JeremySells\AddressBook\Libraries\Theme\ThemeLibrary;
use JeremySells\AddressBook\Repositories\ContactRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \JeremySells\AddressBook\Controllers\DefaultController
 */
class DefaultControllerTest extends TestCase
{
    /**
     * @var DefaultController
     */
    private $object;

    /**
     * @var MockObject
     */
    private $mockThemeLibrary;

    /**
     * @var MockObject
     */
    private $mockContactRepository;

    /**
     * @inheritdoc
     */
    public function setup()
    {
        $this->mockThemeLibrary = $this->createMock(ThemeLibrary::class);
        $this->mockContactRepository = $this->createMock(ContactRepository::class);
        $this->object = new DefaultController(
            $this->mockThemeLibrary,
            $this->mockContactRepository
        );
    }

    /**
     * @covers ::__construct()
     * @covers ::homepage()
     */
    public function testHomepage()
    {
        //Prepare Mocks
        $this->mockThemeLibrary->expects($this->once())->method('renderControllerMethod')
            ->with(
                $this->equalTo('Welcome'),
                DefaultController::class . '::homepage'
            )
            ->will($this->returnValue('I am the page content'))
        ;

        //Call Function
        /** @var HttpOutput $result */
        $result = $this->object->homepage();

        //Test Result
        $this->assertInstanceOf(HttpOutput::class, $result);
        $this->assertEquals(200, $result->getHttpStatusCodeCode());
        $this->assertEquals('I am the page content', $result->getContent());
    }

    /**
     * @covers ::__construct()
     * @covers ::get404()
     */
    public function testGet404()
    {
        //Prepare Mocks
        $this->mockThemeLibrary->expects($this->once())->method('renderControllerMethod')
            ->with(
                $this->equalTo('404 Page Not Found'),
                DefaultController::class . '::get404'
            )
            ->will($this->returnValue('I am the page content'))
        ;

        //Call Function
        /** @var HttpOutput $result */
        $result = $this->object->get404();

        //Test Result
        $this->assertInstanceOf(HttpOutput::class, $result);
        $this->assertEquals(404, $result->getHttpStatusCodeCode());
        $this->assertEquals('I am the page content', $result->getContent());
    }
}
