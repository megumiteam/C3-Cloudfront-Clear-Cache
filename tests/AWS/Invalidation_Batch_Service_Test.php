<?php
namespace C3_CloudFront_Cache_Controller\Test\AWS;
use C3_CloudFront_Cache_Controller\AWS;

class Invalidation_Batch_Service_Test extends \WP_UnitTestCase {
    public function setUp() {
		/** @var WP_Rewrite $wp_rewrite */
		global $wp_rewrite;

        parent::setUp();

        /**
         * Change the permalink structure
         */
		$wp_rewrite->init();
		$wp_rewrite->set_permalink_structure( '/%postname%/' );
    }

    public function test_get_the_published_post_invalidation_paths() {
        $post = $this->factory->post->create_and_get( array(
            'post_status' => 'publish',
            'post_name' => 'hello-world',
        ) );

		$target = new AWS\Invalidation_Batch_Service();
        $result = $target->create_batch_by_post( 'localhost', 'EXXX', $post );
        $this->assertEquals( array(
            'Items' => array(
                'localhost',
                '/hello-world/*',
            ),
            'Quantity' => 2
        ), $result[ 'InvalidationBatch' ][ 'Paths' ] );
    }
    public function test_get_the_un_published_post_invalidation_paths() {
        $post = $this->factory->post->create_and_get( array(
            'post_status' => 'trash',
            'post_name' => 'hello-world',
            'post_type' => 'post',
        ) );
		$target = new AWS\Invalidation_Batch_Service();
        $result = $target->create_batch_by_post( 'localhost', 'EXXX', $post );
        $this->assertEquals( array(
            'Items' => array(
                'localhost',
                '/hello-world/*',
            ),
            'Quantity' => 2
        ) , $result[ 'InvalidationBatch' ][ 'Paths' ] );
    }

    public function test_get_invalidation_path_for_all() {
		$target = new AWS\Invalidation_Batch_Service();
        $result = $target->create_batch_for_all( 'EXXXX' );
        $this->assertEquals( array(
            'Items' => array(
                '/*'
            ),
            'Quantity' => 1
        ) , $result[ 'InvalidationBatch' ][ 'Paths' ] );
    }

    /**
     * @dataProvider provide_create_batch_by_posts_test_case
     */
    public function test_create_batch_by_posts( $posts = [], $expected ) {
		$target = new AWS\Invalidation_Batch_Service();
        $result = $target->create_batch_by_posts( 'localhost', 'EXXXX', $posts );
        $this->assertEquals( $expected, $result[ 'InvalidationBatch' ][ 'Paths' ] );
    }
    public function provide_create_batch_by_posts_test_case() {
        return [
            [
                [
                    $this->factory->post->create_and_get( array(
                        'post_status' => 'publish',
                        'post_name' => 'hello-world',
                    ) )
                ],
                [
                    "Items" => [
                        "localhost",
                        "/hello-world/*"
                    ],
                    "Quantity" => 2
                ]
                ],
                [
                    [
                        $this->factory->post->create_and_get( array(
                            'post_status' => 'publish',
                            'post_name' => 'see-you',
                        ) ),
                        $this->factory->post->create_and_get( array(
                            'post_status' => 'trash',
                            'post_name' => 'good-bye',
                        ) )
                    ],
                    [
                        "Items" => [
                            "localhost",
                            "/see-you/*",
                            "/good-bye/*"
                        ],
                        "Quantity" => 3
                    ]
                ]
         ];
    }
    
}