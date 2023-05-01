import 'package:flutter/foundation.dart';
import 'package:get/get.dart';

import '../models/Transaction.dart';
import '../providers/data_provider.dart';
import 'auth_controller.dart';

class TransactionController extends GetxController {
  DataProvider dataProvider = DataProvider();
  AuthController authController = Get.put(AuthController());

  final _isTransactionsLoaded = false.obs;
  bool get isTransactionsLoaded => _isTransactionsLoaded.value;

  final transactions = <Transaction>[].obs;

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
    await getAllTransactions();
  }

  Future getAllTransactions() async {
    _isTransactionsLoaded.value = false;
    dataProvider.getTransactions(token: authController.token).then((value) {
      if (kDebugMode) {
        print('TRANSACTIONS RESPONSE: $value');
      }

      if (value['success'] == true) {
        transactions.value = value['data']
            .map<Transaction>((e) => Transaction.fromJson(e))
            .toList();
        _isTransactionsLoaded.value = false;
      } else {
        _isTransactionsLoaded.value = false;
      }
    });
  }
}
