// Document Ready Functions

let debounceSearch;

/**
 * FUNGSI HAPUS DATA
 */
function deleteTable(event) {
  event.preventDefault(); // Mencegah aksi default jika dipanggil dari event click

  let button = $(this);

  const formData = new FormData();

  let id = button.data("id");
  let table = button.data("table");
  let id_name = button.data("id_name");

  formData.append("id", id);
  formData.append("table_name", table);
  formData.append("id_name", id_name);

  fetch("./php/delete_table.php", { method: "POST", body: formData })
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      $("#deleteModalPopUp").css("display", "flex");
      $("#deleteModalPopUp .modal-content h3").text(data.message);

      setTimeout(() => {
        $("#deleteModalPopUp").css("display", "none");
        location.reload();
      }, 1000);
    });
}

/**
 * EDIT DATA PEMBAYARAN SURAT BAPTIS
 */
function editDataPembayaran(event) {
  event.preventDefault();

  let button = $(this);

  let id = button.data("id");
  let nik = button.data("nik");
  let nama_lengkap = button.data("nama_lengkap");
  let tanggal_baptis = button.data("tanggal_baptis");

  $("#editIdPembayaran").val(id);
  $("#editNIKPembayaran").val(nik);
  $("#editNamaLengkapPembayaran").val(nama_lengkap);
  $("#editTanggalBaptisPembayaran").val(tanggal_baptis);

  $("#formEditPembayaranBaptis").css("display", "flex");
}

/**
 * EDIT DATA SAKRAMEN DEWASA
 */
function editDataDewasa(event) {
  event.preventDefault(); // Mencegah aksi default jika dipanggil dari event click
  // Ambil data dari elemen tombol yang diklik
  let button = $(this);
  let id = button.data("id");
  // let id_admin = button.data("id_admin");
  let nomor_lb = button.data("nomor_lb");
  let nomor_sa = button.data("nomor_sa");
  let nik = button.data("nik");
  let nama_diri = button.data("nama_diri");
  let nama_pemandian = button.data("nama_pemandian");
  let nama_ayah = button.data("nama_ayah");
  let nama_ibu = button.data("nama_ibu");
  let nama_suami_istri = button.data("nama_suami_istri");
  let tempat_pernikahan = button.data("tempat_pernikahan");
  let tanggal_pernikahan = button.data("tanggal_pernikahan");
  let tempat_permandian = button.data("tempat_permandian");
  let tanggal_permandian = button.data("tanggal_permandian");
  let saksi_permandian = button.data("saksi_permandian");
  let lm_paroki = button.data("lm_paroki");
  let pastor_pembaptis = button.data("pastor_pembaptis");
  let alamat = button.data("alamat");
  let no_hp = button.data("no_hp");
  let jumlah_katekumen = button.data("jumlah_katekumen");

  // Masukkan data ke dalam form edit
  $("#editIddewasa").val(id);
  // $("#editIdAdmindewasa").val(id_admin);
  $("#editNoLB").val(nomor_lb);
  $("#editNoSA").val(nomor_sa);
  $("#editNIK").val(nik);
  $("#editNamaLeng").val(nama_diri);
  $("#editNamaMandi").val(nama_pemandian);
  $("#editAyah").val(nama_ayah);
  $("#editIbu").val(nama_ibu);
  $("#editNamaPasangan").val(nama_suami_istri);
  $("#editTempatPernikahan").val(tempat_pernikahan);
  $("#editTanggalNikah").val(tanggal_pernikahan);
  $("#editTempatMandi").val(tempat_permandian);
  $("#editTanggalMandi").val(tanggal_permandian);
  $("#editSaksiMandi").val(saksi_permandian);
  $("#editLM").val(lm_paroki);
  $("#editBaptis").val(pastor_pembaptis);
  $("#editAlamats").val(alamat);
  $("#editHandpon").val(no_hp);
  $("#editJumlahKatekumenSakramenDewasa").val(jumlah_katekumen);

  // Tampilkan modal atau form edit
  $("#editDatabaseDewasa").css("display", "flex"); // Gunakan modal Bootstrap jika applicable
}

/**
 * EDIT DATA SAKRAMEN BAYI
 */
function editDataBayi(event) {
  event.preventDefault(); // Mencegah aksi default jika dipanggil dari event click
  // Ambil data dari elemen tombol yang diklik
  let button = $(this);
  let id = button.data("id");
  // let id_admin = button.data("id_admin");
  let nomor_lb = button.data("nomor_lb");
  let nomor_sa = button.data("nomor_sa");
  let nik = button.data("nik");
  let nama_diri = button.data("nama_diri");
  let nama_pemandian = button.data("nama_pemandian");
  let tempat_lahir = button.data("tempat_lahir");
  let tanggal_lahir = button.data("tanggal_lahir");
  let nama_ayah = button.data("nama_ayah");
  let nama_ibu = button.data("nama_ibu");
  let tempat_pernikahan = button.data("tempat_pernikahan");
  let tanggal_pernikahan = button.data("tanggal_pernikahan");
  let lm_paroki = button.data("lm_paroki");
  let pastor_memberkati = button.data("pastor_memberkati");
  let tempat_permandian = button.data("tempat_permandian");
  let tanggal_permandian = button.data("tanggal_permandian");
  let saksi_permandian = button.data("saksi_permandian");
  let pastor_pembaptis = button.data("pastor_pembaptis");
  let alamat = button.data("alamat");
  let no_telp = button.data("no_telp");

  // Masukkan data ke dalam form edit
  $("#editId").val(id);
  // $("#editIdAdmin").val(id_admin);
  $("#editNomorLB").val(nomor_lb);
  $("#editNomorSA").val(nomor_sa);
  $("#editNik").val(nik);
  $("#editNama").val(nama_diri);
  $("#editNamaPermandian").val(nama_pemandian);
  $("#editTempatLahir").val(tempat_lahir);
  $("#editTanggalLahir").val(tanggal_lahir);
  $("#editNamaAyah").val(nama_ayah);
  $("#editNamaIbu").val(nama_ibu);
  $("#editGerejaPernikahan").val(tempat_pernikahan);
  $("#editTanggalPernikahan").val(tanggal_pernikahan);
  $("#editLMParoki").val(lm_paroki);
  $("#editPastorBerkat").val(pastor_memberkati);
  $("#editTempatPermandian").val(tempat_permandian);
  $("#editTanggalPermandian").val(tanggal_permandian);
  $("#editSaksi").val(saksi_permandian);
  $("#editPembaptis").val(pastor_pembaptis);
  $("#editAlamat").val(alamat);
  $("#editHp").val(no_telp);

  // Tampilkan modal atau form edit
  $("#editDatabaseBayi").css("display", "flex"); // Gunakan modal Bootstrap jika applicable
}

/**
 * EDIT DATA KATEKUMEN DEWASA
 */
function editDataKatekumenDewasa(e) {
  e.preventDefault();
  let button = $(this);

  let id = button.data("id_katedewasa");
  let nik = button.data("nik");
  let nama_lengkap = button.data("nama_lengkap");
  let nama_baptis = button.data("nama_baptis");
  let tempat_lahir = button.data("tempat_lahir");
  let tanggal_lahir = button.data("tanggal_lahir");
  let nama_ayah = button.data("nama_ayah");
  let nama_ibu = button.data("nama_ibu");
  let tanggal_perkawinan_gereja = button.data("tanggal_perkawinan_gereja");
  let tanggal_perkawinan_adat = button.data("tanggal_perkawinan_adat");
  let nama_gereja = button.data("nama_gereja");
  let nama_suami_istri = button.data("nama_suami_istri");
  let alamat = button.data("alamat");
  let no_telp = button.data("no_telp");
  let jumlah_katekumen = button.data("jumlah_katekumen");

  $("#editIdKateDewasa").val(id);
  $("#editNIKKateDewasa").val(nik);
  $("#editNamaLengkapKateDewasa").val(nama_lengkap);
  $("#editNamaBaptisKateDewasa").val(nama_baptis);
  $("#editTempatLahirKateDewasa").val(tempat_lahir);
  $("#editTanggalLahirKateDewasa").val(tanggal_lahir);
  $("#editAyahKateDewasa").val(nama_ayah);
  $("#editIbuKateDewasa").val(nama_ibu);
  $("#editTanggalPerkawinanGerejaKateDewasa").val(tanggal_perkawinan_gereja);
  $("#editTanggalPerkawinanAdatKateDewasa").val(tanggal_perkawinan_adat);
  $("#editNamaGerejaKateDewasa").val(nama_gereja);
  $("#editNamaSuamiIstriKateDewasa").val(nama_suami_istri);
  $("#editAlamatKateDewasa").val(alamat);
  $("#editNoHpKateDewasa").val(no_telp);
  $("#editJumlahKatekumenDewasa").val(jumlah_katekumen);

  $("#editDatabaseKatekumenDewasa").css("display", "flex");
}

/**
 * EDIT DATA KATEKUMEN ANAK
 */
function editDataKatekumenAnak(e) {
  e.preventDefault();

  let button = $(this);

  let id = button.data("id_kateanak");
  let nik = button.data("nik");
  let nama_lengkap = button.data("nama_lengkap");
  let nama_ayah = button.data("nama_ayah");
  let nama_ibu = button.data("nama_ibu");
  let tanggal_katekumen = button.data("tanggal_katekumen");
  let alamat = button.data("alamat");
  let detail_alamat = button.data("detail_alamat");
  let no_telp = button.data("no_telp");
  let sd_kelas = button.data("sd_kelas");
  let nama_sekolah = button.data("nama_sekolah");
  let jumlah_katekumen = button.data("jumlah_katekumen");

  $("#editIdKateAnak").val(id);
  $("#editNIKKateAnak").val(nik);
  $("#editNamaLengkapKateAnak").val(nama_lengkap);
  $("#editAyahKateAnak").val(nama_ayah);
  $("#editIbuKateAnak").val(nama_ibu);
  $("#editTanggalKatekumenAnak").val(tanggal_katekumen);
  $("#editAlamatKateAnak").val(alamat);
  $("#editDetailAlamatKateAnak").val(detail_alamat);
  $("#editNamaSekolahKateAnak").val(nama_sekolah);
  $("#editNoHpKateAnak").val(no_telp);
  $("#editSdKelasKateAnak").val(sd_kelas);
  $("#editJumlahKatekumenAnak").val(jumlah_katekumen);

  $("#editDatabaseKatekumenAnak").css("display", "flex");
}

/**
 * EDIT DATA BIDUK
 */
function editDataBiduk(e) {
  e.preventDefault();

  let button = $(this);

  let id = button.data("id");
  let nik = button.data("nik");
  let nama_keluarga = button.data("nama_keluarga");
  let alamat = button.data("alamat");

  $("#editIdBiduk").val(id);
  $("#editNIKBiduk").val(nik);
  $("#editNamaKeluargaBiduk").val(nama_keluarga);
  $("#editAlamatBiduk").val(alamat);

  $("#editDatabaseBiduk").css("display", "flex");
}

/**
 * EDIT DATA SURAT BAPTIS
 */
function editDataBaptis(e) {
  e.preventDefault();

  let button = $(this);

  let id = button.data("id");
  let id_admin = button.data("id_admin");
  let no_buku = button.data("no_buku");
  let no_hal = button.data("no_hal");
  let no_baptis = button.data("no_baptis");
  let nik = button.data("nik");
  let tanggal_lahir = button.data("tanggal_lahir");
  let tempat_lahir = button.data("tempat_lahir");
  let tanggal_permandian = button.data("tanggal_permandian");
  let tahun_permandian = button.data("tahun_permandian");
  let tempat_permandian = button.data("tempat_permandian");
  let nama_lengkap = button.data("nama_lengkap");
  let jenis_kelamin = button.data("jenis_kelamin");
  let nama_ayah = button.data("nama_ayah");
  let nama_ibu = button.data("nama_ibu");
  let nama_wali_baptis = button.data("nama_wali_baptis");
  let nama_pembaptis = button.data("nama_pembaptis");
  let tanggal_penguatan = button.data("tanggal_penguatan");
  let tempat_penguatan = button.data("tempat_penguatan");
  let nama_pasangan = button.data("nama_pasangan");
  let tanggal_pernikahan = button.data("tanggal_pernikahan");
  let tempat_pernikahan = button.data("tempat_pernikahan");
  let tanggal_komuni = button.data("tanggal_komuni");
  let alamat = button.data("alamat");
  let nama_baptis = button.data("nama_baptis");
  let nama_pastor_paroki = button.data("nama_pastor_paroki");

  $("#editIdBaptis").val(id);
  $("#editIdAdminBaptis").val(id_admin);
  $("#editNoBukuBaptis").val(no_buku);
  $("#editNoHalBaptis").val(no_hal);
  $("#editNoBaptisBaptis").val(no_baptis);
  $("#editNikBaptis").val(nik);
  $("#editTanggalLahirBaptis").val(tanggal_lahir);
  $("#editTempatLahirBaptis").val(tempat_lahir);
  $("#editTanggalPermandianBaptis").val(tanggal_permandian);
  $("#editTahunPermandianBaptis").val(tahun_permandian);
  $("#editTempatPermandianBaptis").val(tempat_permandian);
  $("#editNamaLengkapBaptis").val(nama_lengkap);
  $("#editJenisKelaminBaptis").val(jenis_kelamin);
  $("#editNamaAyahBaptis").val(nama_ayah);
  $("#editNamaIbuBaptis").val(nama_ibu);
  $("#editNamaWaliBaptisBaptis").val(nama_wali_baptis);
  $("#editNamaPembaptisBaptis").val(nama_pembaptis);
  $("#editTanggalPenguatanBaptis").val(tanggal_penguatan);
  $("#editTempatPenguatanBaptis").val(tempat_penguatan);
  $("#editNamaPasanganBaptis").val(nama_pasangan);
  $("#editTanggalPernikahanBaptis").val(tanggal_pernikahan);
  $("#editTempatPernikahanBaptis").val(tempat_pernikahan);
  $("#editTanggalKomuniBaptis").val(tanggal_komuni);
  $("#editAlamatBaptis").val(alamat);
  $("#editNamaBaptisBaptis").val(nama_baptis);
  $("#editBaptisPastorParoki").val(nama_pastor_paroki);

  $("#editDatabaseBaptis").css("display", "flex");
}

/**
 * EDIT DATA PASTOR PAROKI
 */

function editPastorParoki(e) {
  e.preventDefault();

  let button = $(this);

  let id = button.data("id");
  let nama = button.data("nama_pastor");

  $("#editIdPastorParoki").val(id);
  $("#editNamaPastorParoki").val(nama);

  $("#formEditPastorParoki").css("display", "flex");
}

/**
 * UNTUK MENGECEK DATA NIK KELUARGA DI DATA BIDUK
 * JIKA ADA SET DATA KE INPUT JIKA TIDAK ADA TAMPILKAN FORM SURAT PENGANTAR
 */
function verifikasiNik(e, target) {
  e.preventDefault();
  const nik = $(`#${target}`).val();

  const foundData = dataBiduk.find((item) => {
    return item.nik === nik;
  });

  $("#nikVerificationPopUp").css("display", "flex");
  $("#informationBiduk").css("display", "none");

  if (foundData) {
    $(".suratPengantar").css("display", "none");
    $(".suratPengantar input").prop("required", false);
    $("#nikVerificationPopUp .modal-content h3").text("NIK Ditemukan");
    $("#informationBiduk").css("display", "block");
    $("#verifNamaAyah").text(foundData.nama_keluarga);
    $("#verifAlamat").text(foundData.alamat);

    $("#createBaptisAlamat").val(foundData.alamat);
  } else {
    $(".suratPengantar").css("display", "block");
    $(".suratPengantar input").prop("required", true);

    $("#nikVerificationPopUp .modal-content h3").text("NIK tidak ditemukan");
    $("#informationBiduk").css("display", "none");
  }
  setTimeout(() => {
    $("#nikVerificationPopUp").css("display", "none");
  }, 1000);
}

/**
 * UNTUK MEMBERIKAN NIK KE DALAM OPTION SURAT BAPTIS
 */
function selectKatekumenTypeChange(e) {
  if (e.target.value === "dewasa") {
    $("#baptisCreateKatekumenId").html(
      dataSakramenDewasa
        .map((item) => {
          return `<option value="${item.id_katedewasa}">${item.nama_lengkap}</option>`;
        })
        .join("") // Convert array to a string
    );
  }
}

/**
 * UNTUK MEMUNCULKAN SERTIFIKAT SURAT BAPTIS
 */
function updateBaptismCertificate(event) {
  const id = event.target.dataset.id;

  const foundBaptisData = dataBaptis.find((item) => {
    return item.id === id;
  });

  if (!foundBaptisData) return;

  const namaBaptis = [undefined, undefined];

  // Mapping object keys to their respective HTML elements
  const mappings = {
    tanggal_lahir: "printTanggalLahir",
    tempat_lahir: "printTempatLahir",
    tanggal_permandian: "printTanggalPermandian",
    tempat_permandian: "printTempatPermandian",
    nama_baptis: "printNamaLengkap",
    nama_lengkap: "printNamaLengkap",
    nama_ayah: "printNamaAyah",
    nama_ibu: "printNamaIbu",
    nama_wali_baptis: "printNamaWaliBaptis",
    nama_pembaptis: "printNamaPembaptis",
    tanggal_penguatan: "printTanggalPenguatan",
    tempat_penguatan: "printTempatPenguatan",
    nama_pasangan: "printMenikahDengan",
    tanggal_komuni: "printTanggalKomuni",
    no_buku: "printNomorBuku",
    no_baptis: "printNomorBaptis",
    no_hal: "printNomorHal",
    nama_pastor_paroki: "printNamaPastorParoki",
  };

  // Loop through mappings and update the elements
  for (const key in mappings) {
    if (foundBaptisData[key] !== undefined) {
      if (key === "nama_lengkap") {
        namaBaptis[1] = foundBaptisData[key].toUpperCase();
      } else if (key === "nama_baptis")
        namaBaptis[0] = foundBaptisData[key].toUpperCase();
      else if (
        (key.includes("tanggal") || key.includes("Tanggal")) &&
        foundBaptisData[key] &&
        foundBaptisData[key] !== "0000-00-00"
      )
        document.getElementById(mappings[key]).textContent = new Date(
          foundBaptisData[key]
        ).toLocaleDateString("id-ID", {
          year: "numeric",
          month: "long",
          day: "numeric",
        });
      else
        document.getElementById(mappings[key]).textContent =
          foundBaptisData[key];
    }

    if (namaBaptis[0] && namaBaptis[1]) {
      document.getElementById(
        "printNamaLengkap"
      ).textContent = `${namaBaptis[0]} ${namaBaptis[1]}`;
    }
  }

  $("#signatureDate").text(
    new Date().toLocaleDateString("id-ID", {
      year: "numeric",
      month: "long",
      day: "numeric",
    })
  );

  $(".baptismCertificate").attr("id", "baptismCertificate");
  $(".print-roll-call").attr("id", "");
  $(".print-report").attr("id", "");

  window.print();
}

/**
 * UNTUK MENYETUJUI SAKRAMEN DEWASA BERDASARKAN NILAI BUTTON data-status
 */
function approveSakramenDewasa(event) {
  event.preventDefault();

  const button = event.target;

  const id = button.dataset.id;
  const status = button.dataset.status;

  const formData = new FormData();

  formData.append("id", id);
  formData.append("status", status);

  fetch("./php/sakramen_dewasa/approve_sakramen_dewasa.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      $("#nikVerificationPopUp").css("display", "flex");
      $("#nikVerificationPopUp .modal-content h3").text(data.message);

      setTimeout(() => {
        $("#nikVerificationPopUp").css("display", "none");
        location.reload();
      }, 1000);
    });
}

/**
 * UNTUK MENYETUJUI SAKRAMEN DEWASA BERDASARKAN NILAI BUTTON data-status
 */
function approveBaptis(event) {
  event.preventDefault();

  const button = event.target;

  const id = button.dataset.id;
  const status = button.dataset.status;

  const formData = new FormData();

  formData.append("id", id);
  formData.append("status", status);

  fetch("./php/baptis/ApproveBaptis.php", { method: "POST", body: formData })
    .then((response) => {
      return response.json();
    })
    .then((data) => {
      $("#nikVerificationPopUp").css("display", "flex");
      $("#nikVerificationPopUp .modal-content h3").text(data.message);

      setTimeout(() => {
        $("#nikVerificationPopUp").css("display", "none");
        location.reload();
      }, 1000);
    });
}

/**
 * UNTUK PILIH TAHUN LAPORAN BAPTIS ATAU ABSEN
 */
function toggleYearSelectionModal(event) {
  const type = event.target.dataset.type;
  $("#typeModal").val(type);

  if (type === "baptis") {
    $(".titleModalTahun").text("Pilih Tahun Laporan Baptis");
    $("#labelTahunModal").text("Tahun Laporan Baptis");
  } else if (type === "absen") {
    $(".titleModalTahun").text("Pilih Tahun Laporan Absen Baptis");
    $("#labelTahunModal").text("Tahun Absen Baptis");
  }

  $("#tahunLaporan").val(new Date().getFullYear());
  $("#tahunLaporanModal").css("display", "flex");
}

/**
 * UNTUK MENANGANI PILIHAN TAHUN LAPORAN BAPTIS ATAU ABSEN
 */
function handleYearSelectionModalPrint(event) {
  event.preventDefault();
  const type = $("#typeModal").val();
  const year = $("#tahunLaporan").val();

  if (type === "baptis") {
    laporanBaptis(year);
  } else if (type === "absen") {
    baptisRollCall(year);
  }
  $("#tahunLaporanModal").css("display", "none");
}

/**
 * UNTUK MEMUNCULKAN LAPORAN BAPTIS
 */
function laporanBaptis(year) {
  fetchData(
    `./php/laporan/LaporanBaptis.php?year=${year}`,
    "#baptismTableBody",
    [
      "created_at",
      "id_admin",
      "nik",
      "nama_lengkap",
      "nama_baptis",
      "nama_ayah",
      "nama_ibu",
      "is_exist",
      "status",
    ],
    undefined,
    undefined,
    undefined,
    undefined,
    undefined,
    (data) => {
      $(".baptismCertificate").attr("id", "");
      $(".print-roll-call").attr("id", "");
      $(".print-report").attr("id", "print-report");

      let registeredCount = 0;
      let unregisteredCount = 0;

      if (data)
        data.forEach((item) => {
          if (item.is_exist === "Data Terdaftar Di Biduk") {
            registeredCount++;
          } else {
            unregisteredCount++;
          }
        });

      $("#print-summary-terdaftar").text(registeredCount);

      $("#print-summary-belum-terdaftar").text(unregisteredCount);

      const today = new Date();
      const day = String(today.getDate()).padStart(2, "0");
      const year = today.getFullYear();
      const monthNames = [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December",
      ];
      const month = monthNames[today.getMonth()];

      const formattedDate = `${day} ${month} ${year}`;
      $("#report-date").text(formattedDate);

      window.print();
    }
  );
}

/**
 * UNTUK MEMUNCULKAN LAPORAN ABSEN BAPTIS
 */
function baptisRollCall(year) {
  fetchData(
    `./php/laporan/baptis_roll_call.php?year=${year}`,
    "#baptismRollCallTable",
    ["id", "nama_lengkap", "nik", ""],
    undefined,
    undefined,
    undefined,
    undefined,
    undefined,
    (data) => {
      $(".baptismCertificate").attr("id", "");
      $(".print-report").attr("id", "");
      $(".print-roll-call").attr("id", "print-roll-call");

      $("#roll-call-date").text(
        new Date().toLocaleDateString("id-ID", {
          year: "numeric",
          month: "long",
          day: "numeric",
          hour: "numeric",
          minute: "numeric",
        })
      );

      window.print();
    }
  );
}

/**
 * UNTUK MENANGANI PENCARIAN NIK KATEKUMEN DEWASA ATAU ANAK DI SURAT BAPTIS KETIKA NIK DIINPUTKAN
 */
function handleBaptisNikSearch(event) {
  if (debounceSearch) {
    clearTimeout(debounceSearch);
  }

  debounceSearch = setTimeout(() => {
    let foundData;
    let foundDataType;

    const foundDataKatekumenDewasa = dataSakramenDewasa.find((item) => {
      return item.nik === event.target.value;
    });

    const foundDataKatekumenAnak = dataSakramenBayi.find((item) => {
      return item.nik === event.target.value;
    });

    if (foundDataKatekumenDewasa) {
      foundDataType = "dewasa";
      foundData = foundDataKatekumenDewasa;
    } else if (foundDataKatekumenAnak) {
      foundDataType = "bayi";
      foundData = foundDataKatekumenAnak;
    }

    if (!foundData) return;

    if (foundData && foundDataType === "dewasa" && foundData.status !== "3")
      return;

    $("#baptisCreateKatekumenType").val(foundDataType);
    if (foundDataType === "dewasa")
      $("#baptisCreateKatekumenId").val(foundData.id);

    handleBaptisTypeChange({ target: { value: foundDataType } });

    if (foundDataType === "dewasa") {
      $("#createBaptisNamaAyah").val(foundData.nama_ayah);
      $("#createBaptisNamaIbu").val(foundData.nama_ibu);
      $("#createBaptisAlamat").val(foundData.alamat);
      $("#createBaptisNoHp").val(foundData.no_hp);
      $("#createBaptisNamaLengkap").val(foundData.nama_diri);
      $("#createBaptisTanggalLahir").val(foundData.tanggal_lahir);
      $("#createBaptisTanggalPermandian").val(foundData.tanggal_permandian);
      $("#createBaptisTempatPermandian").val(foundData.tempat_permandian);
      $("#createBaptisTanggalPernikahan").val(foundData.tanggal_pernikahan);
      $("#createBaptisTempatPernikahan").val(foundData.tempat_pernikahan);
      $("#createBaptisNamaPasangan").val(foundData.nama_suami_istri);
      $("#createBaptisNamaPembaptis").val(foundData.pastor_pembaptis);
    } else if (foundDataType === "bayi") {
      $("#createBaptisNamaAyah").val(foundData.nama_ayah);
      $("#createBaptisNamaIbu").val(foundData.nama_ibu);
      $("#createBaptisAlamat").val(foundData.alamat);
      $("#createBaptisNoHp").val(foundData.no_telp);
      $("#createBaptisNamaLengkap").val(foundData.nama_diri);
      $("#createBaptisTanggalLahir").val(foundData.tanggal_lahir);
      $("#createBaptisTanggalPermandian").val(foundData.tanggal_permandian);
      $("#createBaptisTempatPermandian").val(foundData.tempat_permandian);
      $("#createBaptisNamaPembaptis").val(foundData.pastor_pembaptis);
      $("#createBaptisTanggalPernikahan").val(foundData.tanggal_pernikahan);
      $("#createBaptisTempatPernikahan").val(foundData.tempat_pernikahan);
      $("#createBaptisTempatLahir").val(foundData.tempat_lahir);
      $("#createBaptisNamaBaptis").val(foundData.nama_permandian);
    }
  }, 1000);
}

/**
 * UNTUK MENANGANI PERUBAHAN TIPE BAPTIS DAN MEMUNCULKAN OPTION SAKRAMEN DEWASA
 */
function handleBaptisTypeChange(event) {
  if (event.target.value === "dewasa") {
    $("#sakramen-selection").css("display", "flex");
  } else {
    $("#sakramen-selection").css("display", "none");
  }
}

/**
 * UNTUK MENANGANI PENCARIAN NIK PEMBAYARAN BAPTIS DAN MENGISI DATA PEMBAYARAN BAPTIS SESUAI NIK
 */
function handleBaptisPembayaranNik(e) {
  if (debounceSearch) {
    formDatabaseBayi;
    clearTimeout(debounceSearch);
  }

  debounceSearch = setTimeout(() => {
    let foundData;

    foundData = dataBaptis.find((item) => {
      return item.nik === e.target.value;
    });

    if (!foundData) return;

    $("#createPembayaranBaptisNamaLengkap").val(foundData.nama_lengkap);
    $("#createPembayaranBaptisTanggalBaptis").val(foundData.tanggal_permandian);
  }, 500);
}

function handleSakramenDewasaNIKSearch(event) {
  if (debounceSearch) {
    clearTimeout(debounceSearch);
  }

  debounceSearch = setTimeout(() => {
    let foundData;

    foundData = dataKatekumenDewasa.find((item) => {
      return item.nik === event.target.value;
    });

    if (!foundData) return;

    $("#createSakramenDewasaNamaDiriLengkap").val(foundData.nama_lengkap);
    $("#createSakramenDewasaTanggalLahir").val(foundData.tanggal_lahir);
    $("#createSakramenDewasaNamaAyah").val(foundData.nama_ayah);
    $("#createSakramenDewasaNamaIbu").val(foundData.nama_ibu);
    $("#createSakramenDewasaAlamat").val(foundData.alamat);
    $("#createSakramenDewasaNoHp").val(foundData.no_telp);
    $("#createSakramenDewasaNamaSuamiIstri").val(foundData.nama_suami_istri);
  }, 500);
}

function validateSakramenDewasaForm() {
    const jumlahKatekumenInput = document.getElementById('jumlahKatekumen');
    const jumlahKatekumen = parseInt(jumlahKatekumenInput.value, 10);
    const messageBox = document.getElementById('messageBox');

    if (isNaN(jumlahKatekumen) || jumlahKatekumen < 60) {
      messageBox.textContent = 'Jumlah Katekumen tidak boleh kurang dari 60.';
      messageBox.style.color = 'red';
      return false; 
    } else {
      messageBox.textContent = ''; 
      messageBox.style.color = '';
      return true;     }
  }