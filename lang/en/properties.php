<?php
return [
    'name' => 'Property Name',
    'description' => 'Description',
    'location' => 'Location',
    'price' => 'Price',
    'status' => 'Status',
    'validation' => [
        'required' => 'This field is required.',
        'max_name' => 'Name cannot be more than 255 characters.',
        'numeric_price' => 'Price must be a number.',
        'invalid_status' => 'Invalid status.',
        'image' => 'Each file must be an image.',
        'mimes_image' => 'Each file must be either a JPEG or PNG image.',
        'max_size' => 'Each image cannot be larger than 2MB.',
    ],
    'success' => [
        'created' => 'Property created successfully.',
        'list_retrieved' => 'List retrieved successfully.',
        'updated' => 'Property updated successfully.',
        'found' => 'Property found.',

    ],
    'error' => [
        'creation_failed' => 'Failed to create property.',
        'not_found' => 'Property not found.',
        'update_failed' => 'Failed to update property.',
    ],

];
