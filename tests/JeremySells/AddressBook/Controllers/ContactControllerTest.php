<?php
declare(strict_types=1);

namespace JeremySells\AddressBook\Controllers;

use JeremySells\AddressBook\Entities\AbstractContactEntity;
use JeremySells\AddressBook\Entities\OrganisationEntity;
use JeremySells\AddressBook\Entities\PersonEntity;
use JeremySells\AddressBook\Libraries\Output\HttpOutput;
use JeremySells\AddressBook\Libraries\Output\RedirectOutput;
use JeremySells\AddressBook\Libraries\Theme\ThemeLibrary;
use JeremySells\AddressBook\Repositories\ContactRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \JeremySells\AddressBook\Controllers\ContactController
 * @backupGlobals enabled
 */
class ContactControllerTest extends TestCase
{
    /**
     * @var ContactController
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
    public function setUp()
    {
        $this->mockThemeLibrary = $this->createMock(ThemeLibrary::class);
        $this->mockContactRepository = $this->createMock(ContactRepository::class);
        $this->object = new ContactController(
            $this->mockThemeLibrary,
            $this->mockContactRepository
        );
    }

    /**
     * @covers ::__construct()
     * @covers ::all()
     * @covers ::index()
     */
    public function testAll()
    {
        //Prepare Mocks
        $mockContacts = [
            new PersonEntity(),
            new OrganisationEntity(),
            new OrganisationEntity(),
            new PersonEntity(),
            new PersonEntity(),
        ];

        $this->mockContactRepository->expects($this->once())->method('findAll')
            ->will($this->returnValue($mockContacts))
        ;
        $this->mockThemeLibrary->expects($this->once())->method('renderControllerMethod')
            ->with(
                $this->equalTo('All Contacts'),
                $this->equalTo(ContactController::class . '::index'),
                $this->equalTo([
                    'contacts' => $mockContacts,
                    'title' => 'All Contacts',
                    'titleSub' => ''
                ])
            )
            ->will($this->returnValue('I am the page content'))
        ;

        //Call Function
        /** @var HttpOutput $result */
        $result = $this->object->all();

        //Test Result
        $this->assertInstanceOf(HttpOutput::class, $result);
        $this->assertEquals(200, $result->getHttpStatusCodeCode());
        $this->assertEquals('I am the page content', $result->getContent());
    }

    /**
     * Tests 'search' with a search query
     * @covers ::__construct()
     * @covers ::search()
     * @covers ::index()
     */
    public function testSearchWithQuery()
    {
        $_GET['q'] = 'Web Search';

        //Prepare Mocks
        $mockContacts = [
            new PersonEntity(),
            new OrganisationEntity(),
            new OrganisationEntity(),
            new PersonEntity(),
            new PersonEntity(),
        ];

        $this->mockContactRepository->expects($this->once())->method('searchResults')
            ->with(
                $this->equalTo('Web Search')
            )
            ->will($this->returnValue($mockContacts))
        ;
        $this->mockContactRepository->expects($this->once())->method('count')
            ->with($this->equalTo([]))
            ->will($this->returnValue(10))
        ;

        $this->mockThemeLibrary->expects($this->once())->method('renderControllerMethod')
            ->with(
                $this->equalTo('Search Contacts'),
                $this->equalTo(ContactController::class . '::index'),
                $this->equalTo([
                    'contacts' => $mockContacts,
                    'title' => 'Search Contacts',
                    'titleSub' => 'Found (5) out of (10) Contacts'
                ])
            )
            ->will($this->returnValue('I am the page content'))
        ;

        //Call Function
        /** @var HttpOutput $result */
        $result = $this->object->search();

        //Test Result
        $this->assertInstanceOf(HttpOutput::class, $result);
        $this->assertEquals(200, $result->getHttpStatusCodeCode());
        $this->assertEquals('I am the page content', $result->getContent());
    }

    /**
     * Tests 'search' with a search query
     * @covers ::__construct()
     * @covers ::search()
     * @covers ::index()
     */
    public function testSearchWithNoQuery()
    {
        //Prepare Mocks
        $mockContacts = [
            new PersonEntity(),
            new OrganisationEntity(),
            new PersonEntity()
        ];

        $this->mockContactRepository->expects($this->once())->method('searchResults')
            ->with(
                $this->equalTo('')
            )
            ->will($this->returnValue($mockContacts))
        ;
        $this->mockContactRepository->expects($this->once())->method('count')
            ->with($this->equalTo([]))
            ->will($this->returnValue(20))
        ;

        $this->mockThemeLibrary->expects($this->once())->method('renderControllerMethod')
            ->with(
                $this->equalTo('Search Contacts'),
                $this->equalTo(ContactController::class . '::index'),
                $this->equalTo([
                    'contacts' => $mockContacts,
                    'title' => 'Search Contacts',
                    'titleSub' => 'Found (3) out of (20) Contacts'
                ])
            )
            ->will($this->returnValue('I am the page content'))
        ;

        //Call Function
        /** @var HttpOutput $result */
        $result = $this->object->search();

        //Test Result
        $this->assertInstanceOf(HttpOutput::class, $result);
        $this->assertEquals(200, $result->getHttpStatusCodeCode());
        $this->assertEquals('I am the page content', $result->getContent());
    }

    /**
     * Tests 'item' by GET id
     * @covers ::__construct()
     * @covers ::item()
     */
    public function testItemById()
    {
        //Prepare Mocks
        $mockContactEntity = $this->createMock(AbstractContactEntity::class);
        $mockContactEntity->expects($this->once())->method('getId')->will($this->returnValue(42));
        $mockContactEntity->expects($this->once())->method('getTypeName')->will($this->returnValue('Contact'));

        $this->mockContactRepository->expects($this->once())->method('findOneBy')
            ->with($this->equalTo(['id' => 42]))
            ->will($this->returnValue($mockContactEntity))
        ;
        $this->mockThemeLibrary->expects($this->once())->method('renderControllerMethod')
            ->with(
                $this->equalTo('Edit Contact'),
                $this->equalTo(ContactController::class . '::item'),
                $this->equalTo([
                    'contactEntity' => $mockContactEntity,
                    'actionName' => 'Edit'
                ])
            )
            ->will($this->returnValue('I am the page content'))
        ;

        //Call Function
        /** @var HttpOutput $result */
        $result = $this->object->item(42);

        //Test Result
        $this->assertInstanceOf(HttpOutput::class, $result);
        $this->assertEquals(200, $result->getHttpStatusCodeCode());
        $this->assertEquals('I am the page content', $result->getContent());
    }

    /**
     * Tests 'item' by POST id
     * @covers ::__construct()
     * @covers ::item()
     */
    public function testItemByPostId()
    {
        $_POST['id'] = 42;

        //Prepare Mocks
        $mockContactEntity = $this->createMock(AbstractContactEntity::class);
        $mockContactEntity->expects($this->once())->method('getId')->will($this->returnValue(42));
        $mockContactEntity->expects($this->once())->method('getTypeName')->will($this->returnValue('Contact'));

        $this->mockContactRepository->expects($this->once())->method('findOneBy')
            ->with($this->equalTo(['id' => 42]))
            ->will($this->returnValue($mockContactEntity))
        ;
        $this->mockThemeLibrary->expects($this->once())->method('renderControllerMethod')
            ->with(
                $this->equalTo('Edit Contact'),
                $this->equalTo(ContactController::class . '::item'),
                $this->equalTo([
                    'contactEntity' => $mockContactEntity,
                    'actionName' => 'Edit'
                ])
            )
            ->will($this->returnValue('I am the page content'))
        ;

        //Call Function
        /** @var HttpOutput $result */
        $result = $this->object->item();

        //Test Result
        $this->assertInstanceOf(HttpOutput::class, $result);
        $this->assertEquals(200, $result->getHttpStatusCodeCode());
        $this->assertEquals('I am the page content', $result->getContent());
    }

    /**
     * Tests 'item' by POST discr (new Person object)
     * @covers ::__construct()
     * @covers ::item()
     */
    public function testItemByPostDiscrPerson()
    {
        $_POST['discr'] = 'person';

        //Prepare Mocks
        $this->mockThemeLibrary->expects($this->once())->method('renderControllerMethod')
            ->with(
                $this->equalTo('Add Person'),
                $this->equalTo(ContactController::class . '::item'),
                $this->callback(function ($value) {
                    return  isset($value['actionName'])
                        &&  $value['actionName'] === 'Add'
                        &&  isset($value['contactEntity'])
                        &&  ($value['contactEntity'] instanceof PersonEntity)
                    ;
                })
            )
            ->will($this->returnValue('I am the page content'))
        ;

        //Call Function
        /** @var HttpOutput $result */
        $result = $this->object->item();

        //Test Result
        $this->assertInstanceOf(HttpOutput::class, $result);
        $this->assertEquals(200, $result->getHttpStatusCodeCode());
        $this->assertEquals('I am the page content', $result->getContent());
    }

    /**
     * Tests 'item' by POST discr (new Organisation object)
     * @covers ::__construct()
     * @covers ::item()
     */
    public function testItemByPostDiscrOrganisation()
    {
        $_POST['discr'] = 'organisation';

        //Prepare Mocks
        $this->mockThemeLibrary->expects($this->once())->method('renderControllerMethod')
            ->with(
                $this->equalTo('Add Organisation'),
                $this->equalTo(ContactController::class . '::item'),
                $this->callback(function ($value) {
                    return  isset($value['actionName'])
                        &&  $value['actionName'] === 'Add'
                        &&  isset($value['contactEntity'])
                        &&  ($value['contactEntity'] instanceof OrganisationEntity)
                        ;
                })
            )
            ->will($this->returnValue('I am the page content'))
        ;

        //Call Function
        /** @var HttpOutput $result */
        $result = $this->object->item();

        //Test Result
        $this->assertInstanceOf(HttpOutput::class, $result);
        $this->assertEquals(200, $result->getHttpStatusCodeCode());
        $this->assertEquals('I am the page content', $result->getContent());
    }

    /**
     * Tests 'item' by Id that's not in the DB
     * @covers ::__construct()
     * @covers ::item()
     * @expectedException \Exception
     */
    public function testItemUnknown()
    {
        //Prepare Mocks
        $this->mockContactRepository->expects($this->once())->method('findOneBy')
            ->with($this->equalTo(['id' => 999]))
            ->will($this->returnValue(null))
        ;

        //Call Function
        /** @var HttpOutput $result */
        $result = $this->object->item(999);

        //Test Result
        $this->assertInstanceOf(HttpOutput::class, $result);
        $this->assertEquals(200, $result->getHttpStatusCodeCode());
        $this->assertEquals('I am the page content', $result->getContent());
    }

    /**
     * Tests 'item' by Id that's not in the DB
     * @covers ::__construct()
     * @covers ::item()
     */
    public function testItemDelete()
    {
        $_POST['delete'] = 'delete';

        //Prepare Mocks
        $mockContactEntity = $this->createMock(AbstractContactEntity::class);

        $this->mockContactRepository->expects($this->once())->method('findOneBy')
            ->with($this->equalTo(['id' => 42]))
            ->will($this->returnValue($mockContactEntity))
        ;
        $this->mockContactRepository->expects($this->once())->method('removeAndFlush')
            ->with($this->equalTo([$mockContactEntity]))
        ;

        //Call Function
        /** @var RedirectOutput $result */
        $result = $this->object->item(42);

        //Test Result
        $this->assertInstanceOf(RedirectOutput::class, $result);
        $this->assertEquals(302, $result->getHttpStatusCodeCode());
        $this->assertEquals('/contacts', $result->getLocation());
    }

    /**
     * Tests saving a contact
     * @covers ::__construct()
     * @covers ::item()
     */
    public function testItemSave()
    {
        $_POST['save'] = 'save';
        $_POST['name'] = 'Jeremy Sells';
        $_POST['contactDetails'] = "123 Example Street\n(05) 555 5555";

        //Prepare Mocks
        $mockContactEntity = $this->createMock(AbstractContactEntity::class);
        $mockContactEntity->expects($this->once())->method('setName')->with($this->equalTo('Jeremy Sells'));
        $mockContactEntity->expects($this->once())->method('setContactDetails')
            ->with($this->equalTo("123 Example Street\n(05) 555 5555"))
        ;
        $mockContactEntity->expects($this->once())->method('getId')->will($this->returnValue(12345));
        $mockContactEntity->expects($this->once())->method('getTypeName')->will($this->returnValue('Contact'));

        $this->mockContactRepository->expects($this->once())->method('findOneBy')
            ->with($this->equalTo(['id' => 12345]))
            ->will($this->returnValue($mockContactEntity))
        ;
        $this->mockContactRepository->expects($this->once())->method('persistAndFlush')
            ->with($this->equalTo([$mockContactEntity]))
        ;
        $this->mockThemeLibrary->expects($this->once())->method('renderControllerMethod')
            ->with(
                $this->equalTo('Edit Contact'),
                $this->equalTo(ContactController::class . '::item'),
                $this->equalTo([
                    'contactEntity' => $mockContactEntity,
                    'actionName' => 'Edit'
                ])
            )
            ->will($this->returnValue('I am the page content'))
        ;

        //Call Function
        /** @var HttpOutput $result */
        $result = $this->object->item(12345);

        //Test Result
        $this->assertInstanceOf(HttpOutput::class, $result);
        $this->assertEquals(200, $result->getHttpStatusCodeCode());
        $this->assertEquals('I am the page content', $result->getContent());
    }
}
