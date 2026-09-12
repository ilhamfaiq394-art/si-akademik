<?php
namespace App\Models;

use InvalidArgumentException;

class Mahasiswa
{
    private ?int $id;
    private string $nim = '';
    private string $nama = '';
    private string $email = '';
    private int $prodiId = 0;
    private string $prodiNama = '';
    private int $angkatan = 0;
    private string $status = 'aktif';

    public function __construct(
        ?int $id = null,
        string $nim = '',
        string $nama = '',
        string $email = '',
        int $prodiId = 0,
        string $prodiNama = '',
        int $angkatan = 0,
        string $status = 'aktif'
    ) {
        $this->id = $id;
        if ($nim !== '') $this->setNim($nim);
        if ($nama !== '') $this->setNama($nama);
        $this->email = $email;
        $this->prodiId = $prodiId;
        $this->prodiNama = $prodiNama;
        $this->angkatan = $angkatan;
        $this->status = $status;
    }

    // --- ID ---
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    // --- NIM (VALIDASI: Huruf & Angka) ---
    public function getNim(): string
    {
        return $this->nim;
    }

    public function setNim(string $nim): void
    {
        $nimClean = trim($nim);
        // Validasi: Harus mengandung minimal 1 huruf DAN minimal 1 angka
        if (!preg_match('/^(?=.*[a-zA-Z])(?=.*[0-9])[a-zA-Z0-9]+$/', $nimClean)) {
            throw new InvalidArgumentException("NIM harus berisi kombinasi huruf dan angka (contoh: E41253154).");
        }
        $this->nim = strtoupper($nimClean);
    }

    // --- NAMA (VALIDASI: Tidak Kosong & Harus Huruf) ---
    public function getNama(): string
    {
        return $this->nama;
    }

    public function setNama(string $nama): void
    {
        $namaClean = trim($nama);
        if (empty($namaClean)) {
            throw new InvalidArgumentException("Nama mahasiswa tidak boleh kosong.");
        }
        if (!preg_match('/^[a-zA-Z\s]+$/', $namaClean)) {
            throw new InvalidArgumentException("Nama mahasiswa hanya boleh berisi huruf dan spasi.");
        }
        $this->nama = $namaClean;
    }

    // --- EMAIL ---
    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = trim($email);
    }

    // --- PRODI ID ---
    public function getProdiId(): int
    {
        return $this->prodiId;
    }

    public function setProdiId(int $prodiId): void
    {
        $this->prodiId = $prodiId;
    }

    // --- PRODI NAMA (Helper) ---
    public function getProdiNama(): string
    {
        return $this->prodiNama;
    }

    public function setProdiNama(string $prodiNama): void
    {
        $this->prodiNama = $prodiNama;
    }

    // --- ANGKATAN ---
    public function getAngkatan(): int
    {
        return $this->angkatan;
    }

    public function setAngkatan(int $angkatan): void
    {
        $this->angkatan = $angkatan;
    }

    // --- STATUS ---
    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }
}