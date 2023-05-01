import 'package:get/get.dart';
import '../providers/data_provider.dart';

class StatsController extends GetxController {
  // final DataProvider _dataProvider = DataProvider();

  // final _dataStation = <DataModel>[].obs;
  // List<DataModel> get dataStation => _dataStation.value;

  final _minX = (0 as double).obs;
  double get minX => _minX.value;
  set minX(double value) => _minX.value = value;

  final _minY = (0 as double).obs;
  double get minY => _minY.value;
  set minY(double value) => _minY.value = value;

  final _maxX = (12 as double).obs;
  double get maxX => _maxX.value;
  set maxX(double value) => _maxX.value = value;

  final _maxY = (2 as double).obs;
  double get maxY => _maxY.value;
  set maxY(double value) => _maxY.value = value;

  final _period = 'month'.obs;
  String get period => _period.value;
  set period(String value) => _period.value = value;

  @override
  void onInit() {
    super.onInit();
  }

  @override
  void onReady() async {
    super.onReady();
  }

  @override
  void onClose() {
    super.onClose();
  }

  _onDtaChanged(value) {
    // print(value.length);
  }
}
