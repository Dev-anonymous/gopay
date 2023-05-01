import 'package:get/get.dart';

import '../providers/data_provider.dart';

class RealTimeController extends GetxController {
  final DataProvider _dataProvider = DataProvider();

  // final _dataStation = <DataModel>[].obs;
  // List<DataModel> get dataStation => _dataStation.value;

  final _isDataLoaded = false.obs;
  bool get isDataLoaded => _isDataLoaded.value;
  set isDataLoaded(bool value) => _isDataLoaded.value = value;

  @override
  void onInit() {
    super.onInit();
  }

  @override
  void onReady() {
    super.onReady();
  }

  @override
  void onClose() {
    super.onClose();
  }
}
