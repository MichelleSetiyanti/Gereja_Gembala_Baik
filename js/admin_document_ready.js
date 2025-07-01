let dataBiduk = [];

let dataSakramenDewasa = [];
let dataSakramenBayi = [];
let dataKatekumenDewasa = [];

let dataBaptis = [];

const baptisType = JSON.parse(localStorage.getItem("user"))["status"] === "4" ? "bayi" : "dewasa";

/**
 * UNTUK MENGAMBIL DATA DARI SERVER / PHP BERDASARKAN URL YANG DIBERIKAN
 */
function fetchData(url, tableBodyId, columns, editCallback, deleteCallback, customId, tableName, printFunction, callback, approveFunction) {
    $.ajax({
        url: url,
        type: "GET",
        dataType: "json",
        success: function (response) {
            let itemId;
            if (response.success) {
                let rows = "";
                if (tableName === "data_biduk") {
                    dataBiduk = response.data;
                } else if (tableName === "sakramen_dewasa") {
                    dataSakramenDewasa = response.data;
                    $("#baptisCreateKatekumenId").html(
                        response.data.map(
                            (item) => {
                                if (item.status === "3") return `<option value="${item.id}">${item.nama_diri}</option>`
                            }
                        )).filter((item) => !item)
                } else if (tableName === "sakramen_bayi") {
                    dataSakramenBayi = response.data;
                } else if (tableName === "katekumen_dewasa") {
                    dataKatekumenDewasa = response.data;
                } else if (tableName === "surat_baptis") {
                    dataBaptis = response.data;
                } else if (tableName === "pastor_paroki") {
                    $("#baptisCreatePastorParoki").html(
                        response.data.map(
                            (item) => {
                                return `<option value="${item.nama_pastor}">${item.nama_pastor}</option>`
                            }
                        )
                    )

                    $("#editBaptisPastorParoki").html(
                        response.data.map(
                            (item) => {
                                return `<option value="${item.nama_pastor}">${item.nama_pastor}</option>`
                            }
                        )
                    )
                }

                if (response?.data?.length === 0) {
                    $(tableBodyId).html(`<tr><td colspan='${columns.length + 1}'>Data kosong</td></tr>`);
                } else {
                    response.data.forEach(function (item) {
                        itemId = item.id;
                        rows += "<tr>";
                        if (item) {

                            columns.forEach(column => {
                                if ((column === "file_kk_ktp" || column === "file_surat_pengantar" || column === "kartu_katekumen") && !!item[column]) {
                                    rows += `<td><a href='${item[column].replace("../", "./")}' target='_blank'>Lihat</a></td>`;
                                    return;
                                } else if (column === "status") {
                                    switch (item[column]) {
                                        case "1":
                                            rows += "<td>Menunggu persetujuan</td>";
                                            break;
                                        case "2":
                                            rows += "<td>Ditolak</td>";
                                            break;
                                        case "3":
                                            rows += "<td>Disetujui</td>";
                                            break;
                                    }
                                } else if (column.includes("tanggal")) {
                                    rows += `<td>${item[column] ? new Date(item[column]).toLocaleDateString("id-ID") : ""}</td>`;
                                } else {
                                    rows += `<td>${item[column] || ""}</td>`;
                                }
                            });
                        }
                        if (location.pathname !== "/" && (JSON.parse(localStorage.getItem("user"))["status"] === "6")) {
                            if (editCallback || deleteCallback || printFunction) {

                                rows += '<td style="display:flex;gap:5px;">';
                                if (editCallback) {
                                    console.log(url.split("."))
                                    rows += `<button class='edit-btn-${url.split(".")[1].split("/").pop()}' data-id='${item.id}' ${generateDataAttributes(item)}>Edit</button>`;
                                }
                                if (deleteCallback) {
                                    rows += `<button data-table='${tableName}' class='delete-btn-${url.split(".")[1].split("/").pop()}'
                            data-id_name='${customId ? customId : "id"}'
                            data-id='${customId ? item[customId] : item.id}'>Delete</button>`;
                                }

                                if (printFunction && item.status === "3") {
                                    if (url.includes("tabelSuratBaptis.php") && JSON.parse(localStorage.getItem("user"))["status"] === "6") {
                                        rows += `<button data-id='${item.id}' onclick='updateBaptismCertificate(event)' class='print-btn-${url.split(".")[0]}'>Print</button>`;
                                    }
                                }
                                if (approveFunction && item.status === "1" && JSON.parse(localStorage.getItem("user"))["status"] === "6" && tableName === "surat_baptis") {
                                    rows += `<button data-id='${item.id}' data-status='3' class='approve-btn-${url.split(".")[0]}' onclick='approveBaptis(event)'>Setujui</button>`;
                                    rows += `<button data-id='${item.id}' data-status='2' class='reject-btn-${url.split(".")[0]}' onclick='approveBaptis(event)'>Tolak</button>`;
                                } else if (approveFunction && item.status === "1" && JSON.parse(localStorage.getItem("user"))["status"] === "6" && tableName === "sakramen") {
                                    rows += `<button data-id='${item.id}' data-status='3' class='approve-btn-${url.split(".")[0]}' onclick='approveSakramenDewasa(event)'>Setujui</button>`;
                                    rows += `<button data-id='${item.id}' data-status='2' class='reject-btn-${url.split(".")[0]}' onclick='approveSakramenDewasa(event)'>Tolak</button>`;
                                }

                            }
                            rows += "</td>";
                        }
                        rows += "</tr>";
                    });
                    $(tableBodyId).html(rows);
                    if (editCallback) $(`.edit-btn-${url.split(".")[1].split("/").pop()}`).click(editCallback)
                    // if (printFunction) $(`.print-btn-${url.split(".")[0]}`).click(() => updateBaptismCertificate(itemId));
                    if (deleteCallback) $(`.delete-btn-${url.split(".")[1].split("/").pop()}`).click(deleteCallback)
                }
            } else {
                $(tableBodyId).html(`<tr><td colspan='${columns.length + 1}'>Data gagal dimuat</td></tr>`);
            }
            if (callback) callback(response.data);

        },
        error: function () {
            $(tableBodyId).html(`<tr><td colspan='${columns.length + 1}'>Terjadi kesalahan</td></tr>`);
        }
    });
}


/**
 * FUNGSI UNTUK MEMBERIKAN ATTRIBUT DATA PADA BUTTON EDIT
 */
function generateDataAttributes(item) {
    return Object.keys(item).map(key => `data-${key}='${item[key] || ""}'`).join(" ");
}

$(document).ready(function () {
    // <!-- Panggil Baptis Dewasa -->
    const user = JSON.parse(localStorage.getItem("user"));
    const isAdmin = user["status"] !== "6";

    fetchData(
        isAdmin ? `./php/sakramen_dewasa/tabelDewasa.php?admin_id=${user["id_admin"]}` : "./php/sakramen_dewasa/tabelDewasa.php",
        "#table-body-dewasa",
        ["id", "id_admin", "nik", "nama_diri", "nama_ayah", "nama_ibu", "nama_suami_istri", "tempat_pernikahan", "tanggal_pernikahan", "saksi_permandian", "lm_paroki", "alamat", "no_hp", "kartu_katekumen", "jumlah_katekumen", "file_kk_ktp", "file_surat_pengantar", "status"],
        editDataDewasa, deleteTable, undefined, "sakramen", undefined, undefined, true
    );

    fetchData(
        isAdmin ? `./php/pembayaran/tabelPembayaranSuratBaptis.php?admin_id=${user["id_admin"]}` : "./php/pembayaran/tabelPembayaranSuratBaptis.php",
        "#table-body-pembayaran",
        ["id", "nik", "nama_lengkap", "tanggal_baptis"],
        editDataPembayaran, deleteTable, undefined, "pembayaran_surat_baptis", undefined, undefined, true
    );

    fetchData(
        isAdmin ? `./php/pastor/tabelPastorParoki.php?admin_id=${user["id_admin"]}` : "./php/pastor/tabelPastorParoki.php",
        "#table-body-pastor",
        ["id", "nama_pastor"],
        editPastorParoki, deleteTable, undefined, "pastor_paroki", undefined, undefined, true
    );


    fetchData(
        isAdmin ? `./php/katekumen_anak/tabelKateAnak.php?admin_id=${user["id_admin"]}` : "./php/katekumen_anak/tabelKateAnak.php",
        "#table-body-kateanak",
        ["id_kateanak", "id_admin", "nik", "nama_lengkap", "nama_ayah", "nama_ibu", "sd_kelas", "nama_sekolah", "tanggal_katekumen", "alamat", "detail_alamat", "no_telp",],
        editDataKatekumenAnak, deleteTable, "id_kateanak", "katekumen_anak"
    );

    fetchData(
        isAdmin ? `./php/katekumen_dewasa/tabelKateDewasa.php?admin_id=${user["id_admin"]}` : "./php/katekumen_dewasa/tabelKateDewasa.php",
        "#table-body-katedewasa",
        ["id_katedewasa", "id_admin", "nik", "nama_lengkap", "tempat_lahir", "tanggal_lahir", "nama_ayah", "nama_ibu", "nama_gereja", "nama_suami_istri", "alamat", "no_telp",],
        editDataKatekumenDewasa, deleteTable, "id_katedewasa", "katekumen_dewasa"
    );

    fetchData(
        isAdmin ? `./php/surat_baptis/tabelSuratBaptis.php?admin_id=${user["id_admin"]}` : "./php/surat_baptis/tabelSuratBaptis.php",
        "#table-body-surat",
        ["id", "id_admin", "no_buku", "no_hal", "no_baptis", "nik", "alamat", "tanggal_lahir", "tempat_lahir", "tanggal_permandian", "tempat_permandian", "nama_lengkap", "nama_baptis", "jenis_kelamin", "nama_ayah", "nama_ibu", "nama_wali_baptis", "nama_pembaptis", "tanggal_penguatan", "tempat_penguatan", "nama_pasangan", "tanggal_pernikahan", "tempat_pernikahan", "tanggal_komuni", "type", "sakramen_id", "nama_pastor_paroki", "status"],
        editDataBaptis, deleteTable, undefined, "surat_baptis", updateBaptismCertificate, undefined, true
    );

    fetchData(
        isAdmin ? `./php/sakramen_bayi/tabelBayi.php?admin_id=${user["id_admin"]}` : "./php/sakramen_bayi/tabelBayi.php",
        "#table-body-bayi",
        ["id", "id_admin", "nik", "nama_diri", "nama_pemandian", "tempat_lahir", "tanggal_lahir", "nama_ayah", "nama_ibu", "tempat_pernikahan", "tanggal_pernikahan", "lm_paroki", "pastor_memberkati", "saksi_permandian", "alamat", "no_telp", "file_kk_ktp", "file_surat_pengantar",],
        editDataBayi, deleteTable, undefined, "sakramen"
    );


    fetchData("./php/biduk/tableDataBiduk.php", "#table-body-biduk", ["id", "id_admin", "nik", "nama_keluarga", "alamat"], editDataBiduk, deleteTable, undefined, "data_biduk");

    // Helper function to get elements
    const getElement = (id) => document.getElementById(id);
    const getElements = (selector) => document.querySelectorAll(selector);

    // Modals
    const modals = {
        sakramen_bayi: getElement("formModal5"),
        sakramen_dewasa: getElement("formModal1"),
        surat_baptis: getElement("formModal2"),
        katekumen_anak: getElement("formModal3"),
        katekumen_dewasa: getElement("formModal4"),
        databaseBayi: getElement("formDatabaseBayi"),
        databaseDewasa: getElement("formDatabaseDewasa"),
        databaseSurat: getElement("formDatabaseSurat"),
        databaseKateAnak: getElement("formDatabaseKateAnak"),
        databaseKateDewasa: getElement("formDatabaseKateDewasa"),
        editBayi: getElement("editDatabaseBayi"),
        editDewasa: getElement("editDatabaseDewasa"),
        editDatabaseKatekumenDewasa: getElement("editDatabaseKatekumenDewasa"),
        editDatabaseKatekumenAnak: getElement("editDatabaseKatekumenAnak"),
        editDatabaseBaptis: getElement("editDatabaseBaptis"),
        deleteModalPopUp: getElement("deleteModalPopUp"),
        databaseBiduk: getElement("formDatabaseBiduk"),
        databasePembayaranSuratBaptis: getElement("formDatabasePembayaranSuratBaptis"),
        data_biduk: getElement("formModalBiduk"),
        pembayaran_surat_baptis: getElement("formPembayaranBaptis"),
        formEditPembayaranBaptis: getElement("formEditPembayaranBaptis"),
        formEditPastorParoki: getElement("formEditPastorParoki"),
        pastor_paroki: getElement("formPastorParoki"),
        databasePastor: getElement("formDatabasePastorParoki"),
        tahunLaporanModal: getElement("tahunLaporanModal"),
    };

    // Buttons
    const buttons = {
        databaseBayi: getElements(".BtnBayi"),
        databaseDewasa: getElements(".BtnDewasa"),
        databaseSurat: getElements(".BtnSurat"),
        databaseKateAnak: getElements(".BtnKateAnak"),
        databaseKateDewasa: getElements(".BtnKateDewasa"),
        databaseBiduk: getElements(".BtnBiduk"),
        databasePembayaranSuratBaptis: getElements(".BtnPembayaranSuratBaptis"),
        databasePastor: getElements(".BtnPastorParoki"),
    };

    const closeButtons = getElements(".close-btn");

    // Hide all modals initially
    Object.values(modals).forEach((modal) => {
        if (modal)
            modal.style.display = "none"
    }
    );

    // Function to open a modal
    const openModal = (modal) => {
        if (modal) modal.style.display = "flex";
        handleUserStatus()
    };

    // Function to close all modals
    const closeAllModals = () => {
        Object.values(modals).forEach((modal) => (modal.style.display = "none"));
    };

    // Attach event listeners to open modal buttons
    Object.keys(buttons).forEach((key) => {
        buttons[key].forEach((btn) => {
            btn.addEventListener("click", () => {
                openModal(modals[key])
            });
        });
    });

    document.querySelectorAll(".open-modal-btn").forEach((btn) => {
        btn.addEventListener("click", function (e) {
            const { table_name } = e.target.dataset;
            openModal(modals[table_name]);
        })
    })

    // Attach event listeners to close buttons
    closeButtons.forEach((btn) => {
        btn.addEventListener("click", closeAllModals);
    });

    // Close modal when clicking outside
    window.addEventListener("click", (event) => {
        Object.values(modals).forEach((modal) => {
            if (event.target === modal) modal.style.display = "none";
        });
    });

    // Handle form submission
    const handleFormSubmission = (formSelector, actionUrl, messageSelector) => {
        const form = document.querySelector(formSelector);
        const messageBox = document.querySelector(messageSelector);

        if (form)
            form.addEventListener("submit", (event) => {
                event.preventDefault();
                const formData = new FormData(form);


                if (actionUrl === "surat_baptis.php" && JSON.parse(localStorage.getItem("user"))["status"] == '4') {
                    formData.append("type", "bayi")
                } else if (actionUrl === "surat_baptis.php" && JSON.parse(localStorage.getItem("user"))["status"] == '3') {
                    formData.append("type", "dewasa")
                }

                formData.append("id_admin", JSON.parse(localStorage.getItem("user"))["id_admin"])


                fetch(actionUrl, { method: "POST", body: formData })
                    .then((response) => response.json())
                    .then((data) => {
                        messageBox.textContent = data.message;
                        messageBox.classList.toggle("success", data.success);
                        messageBox.classList.toggle("error", !data.success);
                        messageBox.style.display = "flex";
                        messageBox.style.opacity = "1";

                        if (data.success)
                            setTimeout(() => {
                                messageBox.style.opacity = "0";
                                messageBox.style.display = "none";

                                location.reload()
                            }, 2000);
                    });
            });
    };

    /**
     * UNTUK MENANGANI SUBMIT DATA DARI MODAL
     */
    handleFormSubmission("#formModal5 .form-container", "./php/sakramen_bayi/sakramen_bayi.php", "#formModal5 #messageBox");
    handleFormSubmission("#formModal1 .form-container", "./php/sakramen_dewasa/sakramen_dewasa.php", "#formModal1 #messageBox");
    handleFormSubmission("#formModal3 .form-container", "./php/katekumen_anak/create_katekumen_anak.php", "#formModal3 #messageBox");
    handleFormSubmission("#formModal4 .form-container", "./php/katekumen_dewasa/create_katekumen_dewasa.php", "#formModal4 #messageBox");
    handleFormSubmission("#formModal2 .form-container", "./php/surat_baptis/surat_baptis.php", "#formModal2 #messageBox");
    handleFormSubmission("#formModalBiduk .form-container", "./php/biduk/database_biduk.php", "#formModalBiduk #messageBox");
    handleFormSubmission("#formPembayaranBaptis .form-container", "./php/pembayaran/pembayaran_surat_baptis.php", "#formPembayaranBaptis #messageBox");
    handleFormSubmission("#editDatabaseBayi .form-container", "./php/sakramen_bayi/UpdateTabelBayi.php", "#editDatabaseBayi #messageBox");
    handleFormSubmission("#editDatabaseDewasa .form-container", "./php/sakramen_dewasa/UpdateTabelDewasa.php", "#editDatabaseDewasa #messageBox");
    handleFormSubmission("#editDatabaseKatekumenDewasa .form-container", "./php/katekumen_dewasa/UpdateTabelKatekumenDewasa.php", "#editDatabaseKatekumenDewasa #messageBox");
    handleFormSubmission("#editDatabaseKatekumenAnak .form-container", "./php/katekumen_anak/UpdateTabelKatekumenAnak.php", "#editDatabaseKatekumenAnak #messageBox");
    handleFormSubmission("#editDatabaseBaptis .form-container", "./php/surat_baptis/UpdateTabelBaptis.php", "#editDatabaseBaptis #messageBox");
    handleFormSubmission("#editDatabaseBiduk .form-container", "./php/biduk/UpdateTabelBiduk.php", "#editDatabaseBiduk #messageBox");
    handleFormSubmission("#formEditPembayaranBaptis .form-container", "./php/pembayaran/UpdatePembayaranSuratBaptis.php", "#formEditPembayaranBaptis #messageBox");
    handleFormSubmission("#formEditPastorParoki .form-container", "./php/pastor/UpdatePastorParoki.php", "#formEditPastorParoki #messageBox");
    handleFormSubmission("#formPastorParoki .form-container", "./php/pastor/pastor_paroki.php", "#formPastorParoki #messageBox");

    /**
     * UNTUK MENANGANI MENU BERDASARKAN STATUS USER
     */
    const handleUserStatus = () => {
        const userData = JSON.parse(localStorage.getItem("user"));

        const lapbaptis = $("card-lapbaptis");
        const sakramenBayi = $("#card-sakramen-bayi");
        const sakramenDewasa = $("#card-sakramen-dewasa");

        const katekumenAnak = $("#card-katekumen-anak");
        const katekumenDewasa = $("#card-katekumen-dewasa");

        const suratBaptis = $("#card-surat-baptis");
        const pembayaranSuratBaptis = $("#card-pembayaran-baptis");
        const pastorParoki = $("#card-pastor-paroki");
        const dataBiduk = $("#card-biduk");

        const selectTipeKatekumenBaptis = $("#katekumen-selection");
        const printLaporanBaptis = $("#printButton");
        const printRollCallBaptis = $("#printRollCallButton")
        const tipeSelection = $("#tipe-selection");
        const allActionTableColumns = $(".action_column");


        allActionTableColumns.css("display", "none");

        /**
         * STATUS USER AWAL
         */
        if (userData.status === "1") {
            katekumenAnak.css("display", "flex");
            katekumenDewasa.css("display", "flex");
            sakramenBayi.css("display", "flex");
        }

        if (userData.status === "2" || userData.status === "3") {
            katekumenAnak.css("display", "flex");
            katekumenDewasa.css("display", "flex");
            sakramenDewasa.css("display", "flex");
        }

        if (userData.status === "4") {
            sakramenBayi.css("display", "flex");
        }

        /**
         * STATUS USER ADMIN BISA MELIHAT SEGALANYA
         */
        if (userData.status === "6") {
            katekumenAnak.css("display", "flex");
            suratBaptis.css("display", "flex");
            katekumenDewasa.css("display", "flex");
            sakramenBayi.css("display", "flex");
            sakramenDewasa.css("display", "flex");
            dataBiduk.css("display", "flex");
            selectTipeKatekumenBaptis.css("display", "flex");
            printLaporanBaptis.css("display", "block");
            tipeSelection.css("display", "block");
            printRollCallBaptis.css("display", "block");
            allActionTableColumns.css("display", "block");
            pembayaranSuratBaptis.css("display", "flex");
            pastorParoki.css("display", "flex");
            lapbaptis.css("display", "flex");
        }
    }

    function refetchUserData() {
        $.ajax({
            url: "./php/user/getUserData.php?userId=" + JSON.parse(localStorage.getItem("user"))["id_admin"],
            type: "GET",
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    localStorage.setItem("user", JSON.stringify(response.data));
                    handleUserStatus();
                }
            }
        })
    }

    refetchUserData()
});