<?php

class themeTest extends WP_UnitTestCase {

    function testTlsThemeIsActive() {
        $this->assertTrue( 'tls' == wp_get_theme() );
    }

}