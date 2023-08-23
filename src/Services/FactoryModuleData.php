<?php

namespace App\Services;


use App\Entity\Module;
use App\Entity\ModuleData;
use App\Repository\ModuleDataRepository;

class FactoryModuleData
{

    public function create(int $nombre, Module $module, ModuleDataRepository $moduleDataRepository , string $type): array
    {

        $listData = [];

        for ($i = 0; $i < $nombre; $i++) {
            $data = new ModuleData();
            $data->setModule($module);
            $data->setTime(new \DateTime());
            $data->setType($type);
            $data->setData(random_int(0,20));

            $moduleDataRepository->save($data, true);



            $listData[] = [
                'id' => $data->getId(),
                'data' => $data->getData(),
                'time' => $data->getTime(), // Formatage possible comme tu le souhaites
                'module' => $data->getModule()->getName(),
                'type' => $data->getType()

            ];
        }
        return $listData;




    }

    private function remplir(ModuleData $data, ?ModuleData $lastData): ModuleData
    {

        if($lastData != null)
        {
            $dataType = $lastData->getType();
        }
        else {
            $dataType = random_int(1,3);
        }


        return $data;
    }
}
