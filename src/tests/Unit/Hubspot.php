<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\CRM\HubSpot as HubspotService;
use Illuminate\Support\Str;

class Hubspot extends TestCase
{

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testCreateNewContact()
    {
        global $global_contact;

        $hubspotService = new HubspotService();

        $lead_data = [
            'firstname' => Str::random(15),
            'lastname' => Str::random(20),
            'email' => strtolower(trim(Str::random(15).'@email.com')),
            'elegido_foto' => 'http://link-foto.test.com',
            'phone' => preg_replace('/[\(\-\) \s]/', '', '11911112222')
        ];

        $contact = $hubspotService->createNewContact($lead_data);

        $this->assertObjectHasAttribute('id', $contact);
        $this->assertObjectHasAttribute('firstname', $contact->properties);
        $this->assertObjectHasAttribute('lastname', $contact->properties);
        $this->assertObjectHasAttribute('email', $contact->properties);
        $this->assertObjectHasAttribute('elegido_foto', $contact->properties);
        $this->assertObjectHasAttribute('phone', $contact->properties);

        $this->assertEquals($lead_data['firstname'], $contact->properties->firstname);
        $this->assertEquals($lead_data['lastname'], $contact->properties->lastname);
        $this->assertEquals($lead_data['email'], $contact->properties->email);
        $this->assertEquals($lead_data['elegido_foto'], $contact->properties->elegido_foto);
        $this->assertEquals($lead_data['phone'], $contact->properties->phone);

        $global_contact = $contact;
    }

    /**
     * @depends testCreateNewContact
     */
    public function testGetContactByEmail()
    {
        $hubspotService = new HubspotService();

        $email = 'fulano.detal.updated@email.com';
        $contact = $hubspotService->getContactByEmail($email);

        $this->assertObjectHasAttribute('id', $contact);
        $this->assertObjectHasAttribute('email', $contact->properties);

        $this->assertEquals($email, $contact->properties->email);

    }

    /**
     * @depends testGetContactByEmail
     */
    public function testUpdateNewContact()
    {
        global $global_contact;

        $hubspotService = new HubspotService();

        $lead_data = [
            'firstname' => $global_contact->properties->firstname.' Updated',
            'lastname' => $global_contact->properties->lastname.' Updated',
            'email' => $global_contact->properties->email,
            'elegido_foto' => $global_contact->properties->elegido_foto.'.updated'
        ];

        $update_response = $hubspotService->updateContact($global_contact, $lead_data);
        $contact = json_decode($update_response->body());

        $this->assertTrue(in_array($update_response->status(), [200]));

        $this->assertObjectHasAttribute('id', $contact);
        $this->assertObjectHasAttribute('firstname', $contact->properties);
        $this->assertObjectHasAttribute('lastname', $contact->properties);
        $this->assertObjectHasAttribute('email', $contact->properties);
        $this->assertObjectHasAttribute('elegido_foto', $contact->properties);

        $this->assertEquals($lead_data['firstname'], $contact->properties->firstname);
        $this->assertEquals($lead_data['lastname'], $contact->properties->lastname);
        $this->assertEquals($lead_data['email'], $contact->properties->email);
        $this->assertEquals($lead_data['elegido_foto'], $contact->properties->elegido_foto);

    }

    /**
     * @depends testUpdateNewContact
     */
    public function testAddContactToList()
    {
        global $global_contact;

        $hubspotService = new HubspotService();

//        #$email = $global_contact->properties->email;
//        $email = 'fulano.detal.updated@email.com';
//        $contact_response = $hubspotService->getContactByEmail($email);
//        $contact = json_decode($contact_response->body())->results[0];
//
//        $this->assertTrue(in_array($contact_response->status(), [200]));

        $add_list_data = [
            'list_id' => env('HUBSPOT_LEAD_LIST_ID'),
            'contacts' => ['vids' => [$global_contact->id]],
        ];
        $update_response = $hubspotService->addContactToList($add_list_data);
        $this->assertTrue($update_response);

    }

    /**
     * @depends testAddContactToList
     */
    public function test_removeContactToList()
    {
        global $global_contact;

        $hubspotService = new HubspotService();

        $add_list_data = [
            'list_id' => env('HUBSPOT_LEAD_LIST_ID'),
            'contacts' => ['vids' => [$global_contact->id]],
        ];
        $http_response = $hubspotService->removeContactToList($add_list_data);
        $this->assertTrue(in_array($http_response->status(), [200]));
    }

}
