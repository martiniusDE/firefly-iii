<?php
/**
 * AttachmentFormRequest.php
 * Copyright (C) 2016 thegrumpydictator@gmail.com
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

declare(strict_types = 1);

namespace FireflyIII\Http\Requests;

/**
 * Class AttachmentFormRequest
 *
 *
 * @package FireflyIII\Http\Requests
 */
class AttachmentFormRequest extends Request
{
    /**
     * @return bool
     */
    public function authorize()
    {
        // Only allow logged in users
        return auth()->check();
    }

    /**
     * @return array
     */
    public function rules()
    {

        return [
            'title'       => 'between:1,255',
            'description' => 'between:1,65536',
            'notes'       => 'between:1,65536',
        ];
    }
}
