<?php


namespace App\Services;


class ChartService
{
    private QRCodeService $qrCodeService;

    /**
     * ChartService constructor.
     * @param QRCodeService $qrCodeService
     */
    public function __construct(QRCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    }

    public function listCharts()
    {

    }
}
