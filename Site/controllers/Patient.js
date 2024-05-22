const { request, response } = require('express');
const express = require('express');
const router = express.Router();
const patientRepo = require('../Repository/patient.repository');


router.get('/', PatientShowAction);
router.get('/OnePatient/:id_patient', ShowOnePatient);


async function PatientShowAction(request, response){
    let patient = await patientRepo.getAllPatient();
    let flashMessage = request.session.flashMessage;
    request.session.flashMessage = "";

    response.render("patient_view", {"Patients":patient, "flashMessage":flashMessage});
}

async function ShowOnePatient(request, response){
    let onepatient = await patientRepo.getOnePatient(request.params.id_patient);
    let flashMessage = request.session.flashMessage;
    request.session.flashMessage = "";
    //console.log(onegame);
    response.render("one_patient", {"onepatient": onepatient, "flashMessage": flashMessage});
}

module.exports = router;

