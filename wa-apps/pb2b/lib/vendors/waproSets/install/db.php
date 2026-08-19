<?php
return array(
    'wapro_image_set' => array(
        'id' => array('int', 11, 'null' => 0, 'autoincrement' => 1),
        'create_datetime' => array('datetime', 'null' => 0),
        ':keys' => array(
            'PRIMARY' => 'id',
        ),
    ),
    'wapro_image_set_items' => array(
        'id' => array('int', 11, 'null' => 0, 'autoincrement' => 1),
        'set_id' => array('int', 11, 'null' => 0),
        'name' => array('varchar', 255, 'null' => 0),
        'sort' => array('int', 11, 'null' => 0, 'default' => '0'),
        'extra' => array('mediumtext', 'null' => 0),
        'description' => array('text', 'null' => 0),
        'width' => array('int', 11, 'null' => 0),
        'height' => array('int', 11, 'null' => 0),
        'size' => array('int', 11, 'null' => 0),
        'filename' => array('varchar', 255, 'null' => 0),
        'original_filename' => array('varchar', 255, 'null' => 0),
        'ext' => array('varchar', 255, 'null' => 0),
        ':keys' => array(
            'PRIMARY' => 'id',
        ),
    ),
	'wapro_file_set' => array(
        'id' => array('int', 11, 'null' => 0, 'autoincrement' => 1),
        'create_datetime' => array('datetime', 'null' => 0),
        ':keys' => array(
            'PRIMARY' => 'id',
        ),
    ),
    'wapro_file_set_items' => array(
        'id' => array('int', 11, 'null' => 0, 'autoincrement' => 1),
        'set_id' => array('int', 11, 'null' => 0),
        'name' => array('varchar', 255, 'null' => 0),
        'sort' => array('int', 11, 'null' => 0, 'default' => '0'),
        'extra' => array('mediumtext', 'null' => 0),
        'description' => array('text', 'null' => 0),
        'filename' => array('varchar', 255, 'null' => 0),
        'original_filename' => array('varchar', 255, 'null' => 0),
        'ext' => array('varchar', 255, 'null' => 0),
        'is_public' => array('tinyint', 1, 'null' => 0, 'default' => '0'),
        ':keys' => array(
            'PRIMARY' => 'id',
        ),
    ),
    'wapro_item_set' => array(
        'id' => array('int', 11, 'null' => 0, 'autoincrement' => 1),
        'create_datetime' => array('datetime', 'null' => 0),
        ':keys' => array(
            'PRIMARY' => 'id',
        ),
    ),
    'wapro_item_set_items' => array(
        'id' => array('int', 11, 'null' => 0, 'autoincrement' => 1),
        'set_id' => array('int', 11, 'null' => 0),
        'name' => array('varchar', 255, 'null' => 0),
        'sort' => array('int', 11, 'null' => 0, 'default' => '0'),
        'extra' => array('mediumtext', 'null' => 0),
        ':keys' => array(
            'PRIMARY' => 'id',
        ),
    ),
);
