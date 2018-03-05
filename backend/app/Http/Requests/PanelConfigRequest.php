<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class PanelConfigRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        #Para hacer que funcione, en el controlador en vez de usar en la funcion Request $request se usa es PanelConfigRequest $request
        return [
            'screen' => 'required|string|size:1|in:Y,N',
            'breadcrumb' => 'required|string|size:1|in:Y,N',
            'box_design' => 'required|string|size:1|in:Y,N',
            'theme_color' => 'required|string|min:5|max:5'
        ];
    }
}
