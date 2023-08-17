<?php

namespace App\Services;


use App\Entity\Module;
use App\Entity\ModuleData;
use App\Repository\ModuleDataRepository;

class FactoryModuleData
{
        // ête lmanque ueut
    public function create(int $nombre, Module $module, ModuleDataRepository $moduleDataRepository , string $type): array
    {
        //$lastData = $moduleDataRepository->findLatestByModule($module);

        $listData = [];

        for ($i = 0; $i < $nombre; $i++) {
            $data = new ModuleData();
            $data->setModule($module);
            $data->setTime(new \DateTime());
            $data->setType($type);
            $data->setData(random_int(0,20));
            //$data = $this->remplir($data, $lastData ?? null);

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

//        switch ($dataType) {
//            case 1:
//            case "Température":
//                $data->setType("Température");
//                $lastValue = $lastData ? $lastData->getData() : random_int(0,20);
//                $data->setData($lastValue + random_int(-2, 2));
//                break;
//            case 2:
//            case "Vitesse":
//                $data->setType("Vitesse");
//                $lastValue = $lastData ?$lastData->getData() :  random_int(20,90);
//                $data->setData($lastValue + random_int(-10, 10));
//                break;
//            case 3:
//            case "Passagers":
//                $data->setType("Passagers");
//                $lastValue = $lastData ?$lastData->getData() :  random_int(20,90);
//                $data->setData($lastValue + random_int(-5, 5));
//                break;
//        }

        return $data;
    }
}
