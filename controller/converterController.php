<?php

use Olifolkerd\Convertor\Convertor;

/**
 * Class converterController
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class converterController extends Controller
{
    /**
     * converterController constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Overview of options.
     */
    public function index()
    {
        $this->set([
            'page_title'       => 'Convert units',
            'meta_description' => 'Quick and easily convert units like weight, temperature and volume using these handy tools.',
            'page_canonical'   => Core::url('converter')
        ]);

        $this->render('index');
    }

    /**
     * Convert weight.
     */
    public function weight()
    {
        $query = $this->request->getQuery();

        $units = [
            'g'         => 'Gram (g)',
            'mg'        => 'Milligram (mg)',
            'kg'        => 'Kilogram (kg)',
            't'         => 'Ton',
            'ons'       => 'Ons',
            'oz'        => 'Ounce',
            'lb'        => 'Pound (lbs)',
            'st'        => 'Stone',
            'N'         => 'Newton',
            'dag'       => 'Decagram (dag)',
            'hg'        => 'Hectogram (hg)',
            'cg'        => 'Centigram (cg)',
            'dg'        => 'Decigram (dg)'
        ];

        $quantity = false;
        $from         = 'kg';
        $to        = 'mg';
        $result      = false;

        if (array_key_exists('quantity', $query)) {
            $quantity = str_replace(',', '.', trim(strip_tags($query['quantity'])));
            $quantity = preg_replace("/[^0-9.]/", "", $quantity);

            if (!$quantity || !strlen($quantity)) {
                $quantity = false;
            }
        }

        if (
            array_key_exists('from', $query) &&
            array_key_exists($query['from'], $units)
        ) {
            $from = $query['from'];
        }

        if (
            array_key_exists('to', $query) &&
            array_key_exists($query['to'], $units)
        ) {
            $to = $query['to'];
        }

        if (false !== $quantity) {
            $convertor = new Convertor();
            $convertor->addUnit('ons', 'kg', 0.1);
            $convertor->addUnit('dag', 'kg', 0.01);
            $convertor->addUnit('hg', 'kg', 0.1);
            $convertor->addUnit('cg', 'kg', 0.00001);
            $convertor->addUnit('dg', 'kg', 0.0001);
            $convertor->from($quantity, $from);
            $result = $convertor->to($to);
        }

        $this->set([
            'page_title'       => 'Convert weight',
            'meta_description' => 'Quick and easily convert weight. For example: from kilogram to milligram.',
            'page_canonical'   => Core::url('converter/weight'),
            'units'            => $units,
            'quantity'      => $quantity,
            'from'              => $from,
            'to'             => $to,
            'result'           => $result
        ]);

        $this->render('weight');
    }

    /**
     * Convert volume.
     */
    public function volume()
    {
        $query = $this->request->getQuery();

        $units = [
            'cc'        => 'CC',
            'ml'        => 'Milliliter (ml)',
            'l'         => 'Liter',
            'kl'        => 'Kiloliter',
            'cup'       => 'Cups',
            'pt'        => 'Pint',
            'gal'       => 'Galon',
            'm3'        => 'Kubieke meter (m3)',
        ];

        $quantity = false;
        $from         = 'l';
        $to        = 'cup';
        $result      = false;

        if (array_key_exists('quantity', $query)) {
            $quantity = str_replace(',', '.', trim(strip_tags($query['quantity'])));
            $quantity = preg_replace("/[^0-9.]/", "", $quantity);

            if (!$quantity || !strlen($quantity)) {
                $quantity = false;
            }
        }

        if (
            array_key_exists('from', $query) &&
            array_key_exists($query['from'], $units)
        ) {
            $from = $query['from'];
        }

        if (
            array_key_exists('to', $query) &&
            array_key_exists($query['to'], $units)
        ) {
            $to = $query['to'];
        }

        if (false !== $quantity) {
            $convertor = new Convertor();
            $convertor->addUnit('cup', 'l', 0.2365882365);
            $convertor->addUnit('cc', 'l', 0.001);
            //$convertor->addUnit('kl', 'l', 1000);
            $convertor->from($quantity, $from);
            $result = $convertor->to($to);
        }

        $this->set([
            'page_title'       => 'Convert volume',
            'meta_description' => 'Quick and easily convert weight. For example: from cups to litres.',
            'page_canonical'   => Core::url('converter/volume'),
            'units'            => $units,
            'quantity'      => $quantity,
            'from'              => $from,
            'to'             => $to,
            'result'           => $result
        ]);

        $this->render('volume');
    }

    /**
     * Convert temperature.
     */
    public function temperature()
    {
        $query = $this->request->getQuery();

        $units = [
            'c'        => 'Celsius (°C)',
            'f'        => 'Fahrenheit (°F)',
            'k'        => 'Kelvin (K)',
            'r'        => 'Reaumur (°R)',
            'ra'       => 'Rankine (°Ra)'
        ];

        $quantity = false;
        $from         = 'c';
        $to        = 'f';
        $result      = false;

        if (array_key_exists('quantity', $query)) {
            $quantity = str_replace(',', '.', trim(strip_tags($query['quantity'])));
            $quantity = preg_replace("/[^0-9.]/", "", $quantity);

            if (!$quantity || !strlen($quantity)) {
                $quantity = false;
            }
        }

        if (
            array_key_exists('from', $query) &&
            array_key_exists($query['from'], $units)
        ) {
            $from = $query['from'];
        }

        if (
            array_key_exists('to', $query) &&
            array_key_exists($query['to'], $units)
        ) {
            $to = $query['to'];
        }

        if (false !== $quantity) {
            $convertor = new Convertor();
            $convertor->addUnit('r', 'k', function($val, $tofrom) {
                return $tofrom?(($val-273.15) * 0.8):(($val / 0.8) + 273.15);
            });
            $convertor->addUnit('ra', 'k', function($val, $tofrom) {
                return $tofrom?($val * 1.8):($val / 1.8);
            });
            $convertor->from($quantity, $from);
            $result = $convertor->to($to);
        }

        $this->set([
            'page_title'       => 'Convert temperature',
            'meta_description' => 'Quick and easily convert weight. For example: from Fahrenheit to Celsius.',
            'page_canonical'   => Core::url('converter/temperature'),
            'units'            => $units,
            'quantity'      => $quantity,
            'from'              => $from,
            'to'             => $to,
            'result'           => $result
        ]);

        $this->render('temperature');
    }
}