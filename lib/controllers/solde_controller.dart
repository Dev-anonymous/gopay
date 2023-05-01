import 'package:flutter/foundation.dart';
import 'package:get/get.dart';
import 'package:gopay/controllers/auth_controller.dart';

import '../models/DemandeTransfert.dart';
import '../models/Transaction.dart';
import '../providers/data_provider.dart';

class SoldeController extends GetxController {
  final DataProvider _dataProvider = DataProvider();
  AuthController authController = Get.put(AuthController());

  final _soldeCDF = "".obs;
  String get soldeCDF => _soldeCDF.value;
  set soldeCDF(String value) => _soldeCDF.value = value;

  final _soldeUSD = "".obs;
  String get soldeUSD => _soldeUSD.value;
  set soldeUSD(String value) => _soldeUSD.value = value;

  final _numeroCompte = "".obs;
  String get numeroCompte => _numeroCompte.value;
  set numeroCompte(String value) => _numeroCompte.value = value;

  final demandeTransferts = <DemandeTransfert>[].obs;
  final transactions = <Transaction>[].obs;

  final _isSoldeUSDLoaded = false.obs;
  bool get isSoldeUSDLoaded => _isSoldeUSDLoaded.value;

  final _isSoldeCDFLoaded = false.obs;
  bool get isSoldeCDFLoaded => _isSoldeCDFLoaded.value;

  /// Numero de compte state
  final _isNCLoaded = false.obs;
  bool get isNCLoaded => _isNCLoaded.value;

  /// Demandes de tranfert state
  final _isDTLoaded = false.obs;
  bool get isDTLoaded => _isDTLoaded.value;

  /// Transactions state
  final _isTransactionsLoaded = false.obs;
  bool get isTransactionsLoaded => _isTransactionsLoaded.value;

  @override
  void onInit() {
    super.onInit();
    init();
  }

  @override
  void onReady() {
    super.onReady();
  }

  @override
  void onClose() {
    super.onClose();
  }

  void init() async {
    _isNCLoaded.value = false;
    _isSoldeUSDLoaded.value = false;
    _isSoldeCDFLoaded.value = false;
    _isDTLoaded.value = false;
    _isTransactionsLoaded.value = false;
    await getNumeroCompte();
    await getSoldeCDF();
    await getSoldeUSD();
    await getTransferts();
    await getTransactions();
  }

  Future getSoldeUSD() async {
    _isSoldeUSDLoaded.value = false;
    return _dataProvider
        .getSolde(solde: 'USD', token: authController.token)
        .then((value) {
      if (kDebugMode) {
        print('USD RESPONSE: $value');
      }

      if (value['success'] == true) {
        soldeUSD = (value['data'] as List).last;
        soldeUSD = '${soldeUSD.split(' ')[0]} ${soldeUSD.split(' ')[1]}';
        print('LE SOLDE EN USD: $soldeUSD');
        _isSoldeUSDLoaded.value = true;
      } else {
        soldeUSD = 'Non Disponible';
        _isSoldeUSDLoaded.value = true;
      }
    });
  }

  Future getSoldeCDF() async {
    _isSoldeCDFLoaded.value = false;
    return _dataProvider
        .getSolde(solde: 'CDF', token: authController.token)
        .then((value) {
      if (kDebugMode) {
        print('CDF RESPONSE: $value');
      }

      if (value['success'] == true) {
        soldeCDF = (value['data'] as List).first;
        soldeCDF = '${soldeCDF.split(' ')[0]} ${soldeCDF.split(' ')[1]}';
        print('LE SOLDE EN CDF: $soldeCDF');
        _isSoldeCDFLoaded.value = true;
      } else {
        soldeCDF = 'Non Disponible';
        _isSoldeCDFLoaded.value = true;
      }
    });
  }

  Future getNumeroCompte() async {
    _isNCLoaded.value = false;
    return _dataProvider
        .getNumeroCompte(token: authController.token)
        .then((value) {
      if (kDebugMode) {
        print('NUMERO DE COMPTE RESPONSE: $value');
      }

      if (value['success'] == true) {
        numeroCompte = value['data'];
        print('NUMERO DE COMPTE: $numeroCompte');
        _isNCLoaded.value = true;
      } else {
        numeroCompte = 'Non Disponible';
        _isNCLoaded.value = true;
      }
    });
  }

  Future getTransferts() async {
    _isDTLoaded.value = false;
    _dataProvider.getTransferts(token: authController.token).then((value) {
      if (kDebugMode) {
        print('GET DEMANDES TRANSFERT: $value');
      }

      if (value['success'] == true) {
        demandeTransferts.value = value['data']
            .map<DemandeTransfert>((e) => DemandeTransfert.fromJson(e))
            .toList();
        _isDTLoaded.value = true;
      } else {
        _isDTLoaded.value = true;
      }
    });
  }

  Future getTransactions() async {
    _isTransactionsLoaded.value = false;
    _dataProvider.getTransactions(token: authController.token).then((value) {
      if (kDebugMode) {
        print('TRANSACTIONS RESPONSE: $value');
      }

      if (value['success'] == true) {
        transactions.value = value['data']
            .map<Transaction>((e) => Transaction.fromJson(e))
            .toList();
        _isTransactionsLoaded.value = true;
      } else {
        _isTransactionsLoaded.value = true;
      }
    });
  }
}
