<?php
/**
 * Created by PhpStorm.
 * User: matthiaslecomte
 * Date: 23/12/2016
 * Time: 00:36
 */

namespace SosBundle\Service;


class HTML2PDF
{
    private $pdf;

    public function create($orientation = null, $format = null, $lang = null, $unicode = null, $encoding = null, $margin = null)
    {
        $this->pdf = new \HTML2PDF(
            $orientation ? $orientation : $this->orientation,
            $format ? $format : $this->format,
            $lang ? $lang : $this->lang,
            $unicode ? $unicode : $this->unicode,
            $encoding ? $encoding : $this->encoding,
            $margin ? $margin : $this->margin

        );

    }

    public function generatePdf($template, $name){
        $this->pdf->writeHTML($template);
        return $this->pdf->Output($name.'.pdf');
        //cette ligne permet de créer la facture sur le serveur et donc de la récupérer via la BDD ;)
        //return $this->pdf->Output(__DIR__ . '/../../../web/facture/Facture.pdf');
    }

}