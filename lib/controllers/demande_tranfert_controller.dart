import 'package:flutter/foundation.dart';
import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:google_fonts/google_fonts.dart';

import '../constants.dart';
import '../models/DemandeTransfert.dart';
import '../providers/data_provider.dart';
import 'auth_controller.dart';

class DemandeTransfertController extends GetxController {
  DataProvider dataProvider = DataProvider();
  AuthController authController = Get.put(AuthController());

  final _isDTLoaded = false.obs;
  bool get isDTLoaded => _isDTLoaded.value;
  set isDTLoaded(bool value) => _isDTLoaded.value = value;

  final demandeTransferts = <DemandeTransfert>[].obs;

  @override
  void onInit() {
    super.onInit();
    init();
  }

  @override
  void onReady() async {
    super.onReady();
  }

  @override
  void onClose() {
    super.onClose();
  }

  void init() async {
    await getAllTransferts();
  }

  Future getAllTransferts() async {
    _isDTLoaded.value = true;
    dataProvider.getTransferts(token: authController.token).then((value) {
      if (kDebugMode) {
        print('GET DEMANDES TRANSFERT: $value');
      }

      if (value['success'] == true) {
        demandeTransferts.value = value['data']
            .map<DemandeTransfert>((e) => DemandeTransfert.fromJson(e))
            .toList();
        _isDTLoaded.value = false;
      } else {
        _isDTLoaded.value = false;
      }
    });
  }

  Future nouvelleDemandeTransfert(
      {required String numero,
      required String montant,
      required String devise}) async {
    _isDTLoaded.value = true;
    dataProvider
        .demanderTransfert(
            token: authController.token,
            devise: devise,
            montant: montant,
            numero: '+243$numero')
        .then((value) {
      if (kDebugMode) {
        print('DEMANDER DE TRANSFERT RESPONSE: $value');
      }

      if (value['success'] == true) {
        _isDTLoaded.value = false;
        getAllTransferts();
        Get.defaultDialog(
            title: 'Demande de transfert',
            middleText: value['message'],
            textConfirm: 'D\'accord',
            middleTextStyle: GoogleFonts.poppins(
              textStyle: const TextStyle(
                  color: Colors.black,
                  fontWeight: FontWeight.w500,
                  fontSize: 13),
            ),
            titleStyle: GoogleFonts.poppins(
              textStyle: const TextStyle(
                  color: Colors.black,
                  fontWeight: FontWeight.w400,
                  fontSize: 13),
            ),
            buttonColor: primaryColor,
            backgroundColor: Colors.white,
            confirmTextColor: Colors.white,
            onConfirm: () {
              Get.back();
            });
      } else {
        _isDTLoaded.value = false;
        Get.defaultDialog(
            title: 'Echec de l\'operqtion',
            middleText: value['message'],
            textConfirm: 'D\'accord',
            middleTextStyle: GoogleFonts.poppins(
              textStyle: const TextStyle(
                  color: Colors.black,
                  fontWeight: FontWeight.w500,
                  fontSize: 13),
            ),
            titleStyle: GoogleFonts.poppins(
              textStyle: const TextStyle(
                  color: Colors.black,
                  fontWeight: FontWeight.w400,
                  fontSize: 13),
            ),
            buttonColor: primaryColor,
            backgroundColor: Colors.white,
            confirmTextColor: Colors.white,
            onConfirm: () {
              Get.back();
            });
      }
    });
  }
}
