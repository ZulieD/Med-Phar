pool = require("../Repository/db.js");


module.exports = {
    getBlankConsole(){
        return {
            "id_patient": 0,
            "prenom": 0,
            "nom": 0,
            "date_naissance": 0,
            "sexe": 0,
            "contraception": 0,
            "poids": 0,
            "taille": 0,
            "allergie": 0,
            "activite_metier": 0,
            "risque_metier": 0,
            "activite_quotidienne": 0,
            "qualite_alimentation": 0
        };
    },
    async getAllPatient(){
        try{
            let conn = await pool.getConnection();// await is used to pause the execution of the current async function 
            //until the promise is resolved. This allows the function to wait for an asynchronous operation to complete before continuing.
            let sql = "SELECT * FROM patient";
            const rows = await conn.query(sql);
            conn.end();// release() method is used to release the memory used by an object
            console.log("ROWS FETCHED: "+rows.length);
            return rows;
        }
        catch (err) {
            console.log(err);
            throw err;
        }
    },
    
    async getOnePatient(id_patient){
        try {
            let conn = await pool.getConnection();
            let sql = "SELECT * FROM patient WHERE id_patient =? ";
            const rows = await conn.query(sql, id_patient);
            conn.release(); // release() method is used to release the memory used by an object
            console.log("ROWS FETCHED: "+rows.length);
            if (rows.length == 1){
                return rows[0];
            }else{
                return false;
            }
        }
        catch (err) {
            console.log(err);
            throw err;
        }
    },
    async delOnePatient(id_patient) {
        try {
            let conn = await pool.getConnection();
            // let sql = "DELETE FROM buy WHERE ID_console=?";//delete buy because it contains also ID_console
            let sql = "DELETE FROM patient WHERE id_patient=?";
            const okPacket = await conn.query(sql, id_patient);
            conn.end();
            console.log(okPacket);
            return okPacket.affectedRows;
        }
        catch (err) {
            console.log(err);
            throw err;
        }
    },
     async addOnePatient(){ 
        try {
            let conn = await pool.getConnection();// await is used to pause the execution of the current async function 
            //until the promise is resolved. This allows the function to wait for an asynchronous operation to complete before continuing.
            let sql = "INSERT INTO patient (id_patient) VALUES (NULL) "; //console ?
            const okPacket = await conn.query(sql); // affectedRows, insertId
            conn.release();// release() method is used to release the memory used by an object
            console.log(okPacket);
            return okPacket.insertId;
        }
        catch (err) {
            console.log(err);
            throw err; 
        }
    },
    async editOnePatient(patientID, patientNom, patientContraception, patientPoids, patientTaille, patientAllergie, patientMetier, patientRisque, patientActQuotidienne, patientAlimentation ){ 
        try {
            let conn = await pool.getConnection();
            let sql = "UPDATE patient SET nom=?, contraception=?, poids=?, taille=?, allergie=?, activite_metier=?, risque_metier=?, activite_quotidienne=?, qualite_alimentation=? WHERE id_patient=? "; // TODO: named parameters? :something
            const okPacket = await conn.query(sql, 
                        [patientID, patientNom, patientContraception, patientPoids , patientTaille, patientAllergie, patientMetier, patientRisque, patientActQuotidienne, patientAlimentation]);
           conn.release();
            console.log(okPacket);
            return okPacket.affectedRows;
        }
        catch (err) {
            console.log(err);
            throw err; 
        }
    },
}
