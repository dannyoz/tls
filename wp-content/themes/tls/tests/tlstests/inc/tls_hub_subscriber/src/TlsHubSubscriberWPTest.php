<?php

use Tls\TlsHubSubscriber\TlsHubSubscriberWP as TlsHubSubscriberWP;

class TlsHubSubscriberWPTest extends WP_UnitTestCase {

    protected $TlsHubSubscriberWPObj;

    protected $data = array(
        'subscription_id'		=> null,
        'topic_url'				=> null,
        'hub_url'               => null,
        'log_messages'			=> null,
        'error_messages'		=> null,
        'subscription_status'	=> null
    );

    public function setUp(){
        parent::setUp();

        $this->TlsHubSubscriberWPObj = new TlsHubSubscriberWP();
    }

    public function testTlsHubSubValidateCanValidateUrl(){
        // Arrange
        $TlsHubSubscriberWP = $this->TlsHubSubscriberWPObj;

        $this->data['topic_url'] = 'http://'; // Should Not Pass
        $url1 = $this->data;

        $this->data['topic_url'] = 'http://foo.bar?q=Spaces should be encoded'; // Should Not Pass
        $url2 = $this->data;

        $this->data['topic_url'] = 'http://0.0.0.0'; // Should Not Pass
        $url3 = $this->data;

        $this->data['topic_url'] = 'http://www.youtube.com'; // Should Pass
        $url4 = $this->data;

        $this->data['topic_url'] = 'http://www.facebook.com'; // Should Pass
        $url5 = $this->data;


        // Act
        $actualResult1 = $TlsHubSubscriberWP->tls_hub_sub_validate($url1);
        $actualResult2 = $TlsHubSubscriberWP->tls_hub_sub_validate($url2);
        $actualResult3 = $TlsHubSubscriberWP->tls_hub_sub_validate($url3);
        $actualResult4 = $TlsHubSubscriberWP->tls_hub_sub_validate($url4);
        $actualResult5 = $TlsHubSubscriberWP->tls_hub_sub_validate($url5);

        // Assert
        $this->assertEquals( null, $actualResult1['topic_url'] ); // URL 1 Assertion
        $this->assertEquals( null, $actualResult2['topic_url'] ); // URL 2 Assertion
        $this->assertEquals( null, $actualResult3['topic_url'] ); // URL 3 Assertion
        $this->assertEquals( 'http://www.youtube.com', $actualResult4['topic_url'] ); // URL 4 Assertion
        $this->assertEquals( 'http://www.facebook.com', $actualResult5['topic_url'] ); // URL 5 Assertion
    }

}
