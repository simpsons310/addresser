<?php

namespace Addresser;

class Addresser
{
    /**
     * @var \Addresser\Loader
     */
    protected $loader;

    /**
     * @var array
     */
    protected $addresses;

    /**
     * @var array
     */
    protected $provinces;

    /**
     * @var array
     */
    protected $districts;

    /**
     * @var array
     */
    protected $wards;

    /**
     * @var array
     */
    protected $keyMap = [
        'name' => 'name',
        'code' => 'code',
        'parent_code' => 'parent_code',
        // 'slug' => 'slug',
        'level' => 'type',
        'path_with_level' => 'name_with_type',
        'districts' => 'quan-huyen',
        'wards' => 'xa-phuong',
        // 'latin'
    ];

    // protected $schemes = [
    //     'provinces' => [
    //         'name',
    //         'code',
    //         'parent_code',
    //         'level',
    //         'path_with_level',
    //         'districts'
    //     ],
    //     'districts' => [
    //         'name',
    //         'code',
    //         'parent_code',
    //         'level',
    //         'path_with_level',
    //         'parent_code',
    //         'wards'
    //     ],
    //     'wards' => [
    //         'name',
    //         'code',
    //         'parent_code',
    //         'level',
    //         'path_with_level',
    //         'parent_code'
    //     ],
    // ];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->loader = new Loader();
        $this->parseAddresses();
    }

    /**
     * @return this
     */
    protected function parseAddresses()
    {
        $rawAddresses = $this->loader->getData();
        $addresses = [];
        $provinces = [];
        $districts = [];
        $wards = [];

        foreach ($rawAddresses as $provinceCode => $provinceData) {
            // Set province data
            $province = $this->parseProvinceData($provinceData);
            $provinces[$provinceCode] = $province;
            // Get districts
            $province['districts'] = [];
            foreach ($this->getRawData($this->getKey('districts'), $provinceData) as $districtCode => $districtData) {
                $district = $this->parseDistrictData($districtData);
                $districts[$districtCode] = $district;
                // Get wards
                $district['wards'] = [];
                foreach ($this->getRawData($this->getKey('wards'), $districtData) as $wardCode => $wardData) {
                    $ward = $this->parseWardData($wardData);
                    $wards[$wardCode] = $ward;
                }
                // Set address data
                $province['districts'][] = $district;
            }
            // Set address data
            $province['districts'] = $province;
            $addresses[$provinceCode] = $province;
        }

        $this->addresses = $addresses;
        $this->provinces = $provinces;
        $this->districts = $districts;
        $this->wards = $wards;

        return $this;
    }

    /**
     * @return array
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    public function getProvinces()
    {
        return $this->provinces;
    }

    public function getDistricts()
    {
        return $this->districts;
    }

    public function getWards()
    {
        return $this->wards;
    }

    protected function getKey($key)
    {
        return isset($this->keyMap[$key]) ? $this->keyMap[$key] : $key;
    }

    protected function getRawData($key, $data, $default = [])
    {
        return isset($data[$key]) ? $data[$key] : $default;
    }

    protected function parseProvinceData($data)
    {
        // TODO: dynamic this function by schema
        return [
            'name' => $data[$this->getKey('name')],
            'code' => $data[$this->getKey('code')],
            'level' => $data[$this->getKey('path_with_level')],
            'path_with_level' => $data[$this->getKey('path_with_level')]
        ];
    }

    protected function parseDistrictData($data)
    {
        // TODO: dynamic this function by schema
        return [
            'name' => $data[$this->getKey('name')],
            'code' => $data[$this->getKey('code')],
            'parent_code' => $data[$this->getKey('parent_code')],
            'level' => $data[$this->getKey('path_with_level')],
            'path_with_level' => $data[$this->getKey('path_with_level')]
        ];
    }

    protected function parseWardData($data)
    {
        // TODO: dynamic this function by schema
        return [
            'name' => $data[$this->getKey('name')],
            'code' => $data[$this->getKey('code')],
            'parent_code' => $data[$this->getKey('parent_code')],
            'level' => $data[$this->getKey('path_with_level')],
            'path_with_level' => $data[$this->getKey('path_with_level')],
        ];
    }
}
