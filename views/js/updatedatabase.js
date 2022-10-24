console.log('activado')
$("#updateDataBase").on("click", function (e) {
    e.preventDefault();
    console.log("click");
    util.alertConfirm("Do you want to update the database?").then(
        (go) => {
            ajax
                .peticion(
                    "normal",
                    {
                        updateBase: "true",
                    },
                    "views/ajax/gestorBaseDatos.php"
                )
                .then(
                    (data) => {
                        console.log(data);
                        if (data.status == "ok") {
                            util.alertSuccess(
                                "Done",
                                "¡The database was updated successfully!",
                                false,
                                true
                            );
                        }
                    },
                    (fail) => {
                        console.log("fallo");
                        console.log(fail);
                    }
                );
        },
        (no) => {
            console.log("no");
        }
    );
});