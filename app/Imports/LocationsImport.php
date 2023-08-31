<?php

namespace App\Imports;

use App\Models\MasterData;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Helpers\SiteHelper;
use Illuminate\Support\Str;

class LocationsImport implements ToCollection
{
    /**
     * @param array $row
     *
     * @return User|null
     */

    public $response;

    public function collection(Collection $rows)
    {
  		$errors = [];
  		$headers = $rows->shift();
  		foreach ($rows as $key => $location) {
	      	\DB::beginTransaction();

	      	try {
	      		$error_ar = [];
	      		$row = $key + 1;

	      		if (trim($location[1]) == '') {
	      			$error_ar[] = 'Location cannot be empty at row '.$row;
	      		}else{
	      			$data['type'] = 'location';
	      			$data['name'] = $location[1];
	      			$data['description'] = $location[0];
	      			if (MasterData::existsData($data)) {
	      				$error_ar[] = 'Location already exists at row '.$row;
	      			}else{
	      				MasterData::create($data);

	      			}

	      		}
	      		if (count($error_ar) < 1) {
		      		\DB::commit();
		      		$error_ar[] = 'Location added successfully at row '.$row;
		      	}else{
		      		\DB::rollback();
		      		$error_ar[] = 'Location added failed at row '.$row;
		      	}
		      	
		      	$errors[] = $error_ar;
		      	
	      	} catch (Exception $e) {
	      		\DB::rollback();
	      		$error_ar[] = 'Error occured '.$e->getMessage().' at row '.$row;
	      		$error_ar[] = 'Record added failed at row '.$row;
	      		$errors[] = $error_ar;
	      		
	      	}

  		}
  		$this->response = $errors;
  		return $this->response;
    }

    public function getResponse(): array
    {
        return $this->response;
    }
}