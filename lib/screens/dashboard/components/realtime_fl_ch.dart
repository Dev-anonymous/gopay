import 'dart:math';

import 'package:gopay/controllers/realtime_controller.dart';
import 'package:fl_chart/fl_chart.dart';
import 'package:flutter/material.dart';
import 'package:get/get.dart';

import '../../../constants.dart';
import 'resume_card.dart';

class _LineChart extends StatelessWidget {
  _LineChart({required this.dataStation, required this.element}) {
    if (element == 'co2') {
      this.maxY = 4001;
    } else if (element == 'h2s') {
      this.maxY = 3;
    } else {
      this.maxY = 11;
    }
  }
  final List dataStation;
  final String element;

  late double maxY;

  @override
  Widget build(BuildContext context) {
    return Container(
      height: 350,
      clipBehavior: Clip.hardEdge,
      decoration: const BoxDecoration(
        borderRadius: BorderRadius.all(Radius.circular(18)),
        color: secondaryColor,
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
              decoration: BoxDecoration(
                color: (element == 'h2s')
                    ? Color(0xff4af699)
                    : (element == 'co2')
                        ? Color(0xFFEE2727)
                        : Color(0xFFFFCF26),
                borderRadius:
                    BorderRadius.only(bottomRight: Radius.circular(18)),
              ),
              child: Padding(
                padding:
                    const EdgeInsets.symmetric(vertical: 8, horizontal: 16),
                child: Text(
                  (element == 'h2s')
                      ? 'Capteur 2'
                      : (element == 'co2')
                          ? 'Capteur 1'
                          : 'Capteur 3',
                  style: const TextStyle(
                      color: Color(0xff2c274c),
                      fontSize: 12,
                      fontWeight: FontWeight.bold),
                ),
              )),
          const SizedBox(
            height: 16,
          ),
          Expanded(
            child: Padding(
              padding: const EdgeInsets.only(right: 16, left: 6),
              child: LineChart(
                sampleData1,
                swapAnimationCurve: Curves.easeInOut,
                swapAnimationDuration: const Duration(milliseconds: 500),
              ),
            ),
          ),
          const SizedBox(
            height: 10,
          ),
        ],
      ),
    );
  }

  LineChartData get sampleData1 => LineChartData(
        lineTouchData: lineTouchData1,
        gridData: gridData,
        titlesData: titlesData1,
        borderData: borderData,
        lineBarsData: lineBarsData1,
        minX: 0,
        maxX: (dataStation.length >= 100)
            ? 99
            : dataStation.length.toDouble() - 1,
        maxY: maxY,
        minY: 0,
      );

  LineTouchData get lineTouchData1 => LineTouchData(
        handleBuiltInTouches: true,
        touchTooltipData: LineTouchTooltipData(
          tooltipBgColor: Colors.blueGrey.withOpacity(0.8),
        ),
      );

  FlTitlesData get titlesData1 => FlTitlesData(
        bottomTitles: AxisTitles(
          sideTitles: bottomTitles,
        ),
        rightTitles: AxisTitles(
          sideTitles: SideTitles(showTitles: false),
        ),
        topTitles: AxisTitles(
          sideTitles: SideTitles(showTitles: false),
        ),
        leftTitles: AxisTitles(
          sideTitles: leftTitles(),
        ),
      );

  List<LineChartBarData> get lineBarsData1 => [lineChartBarData];

  Widget leftTitleWidgets(double value, TitleMeta meta) {
    const style = TextStyle(
      color: Color(0xff75729e),
      fontWeight: FontWeight.bold,
      fontSize: 14,
    );
    String text;
    if (element == 'co2') {
      switch (value.toInt()) {
        case 500:
          text = '500';
          break;
        case 1000:
          text = '1000';
          break;
        case 2000:
          text = '2000';
          break;
        case 3000:
          text = '3000';
          break;
        case 4000:
          text = '4000';
          break;
        case 5000:
          text = '';
          break;
        default:
          return Container();
      }
    } else if (element == 'h2s') {
      switch (value.toInt()) {
        case 0:
          text = '0';
          break;
        case 1:
          text = '1';
          break;
        case 2:
          text = '2';
          break;
        default:
          return Container();
      }
    } else {
      switch (value.toInt()) {
        case 0:
          text = '0';
          break;

        case 2:
          text = '2';
          break;

        case 4:
          text = '4';
          break;
        case 5:
          text = '5';
          break;
        case 6:
          text = '6';
          break;

        case 8:
          text = '8';
          break;

        case 10:
          text = '10';
          break;
        default:
          return Container();
      }
    }
    return Text(text, style: style, textAlign: TextAlign.center);
  }

  SideTitles leftTitles() => SideTitles(
        getTitlesWidget: leftTitleWidgets,
        showTitles: true,
        interval: 1,
        reservedSize: 40,
      );

  Widget bottomTitleWidgets(double value, TitleMeta meta) {
    const style = TextStyle(
      color: Color(0xff72719b),
      fontWeight: FontWeight.bold,
      fontSize: 14,
    );
    Widget text;
    switch (value.toInt()) {
      case 0:
        text = RotatedBox(
          quarterTurns: 3,
          child: Text(
              '${dataStation[0].date!.toDate().toString().split(' ')[1].split('.')[0]}',
              style: style),
        );
        break;
      case 10:
        text = RotatedBox(
          quarterTurns: 3,
          child: Text(
              '${dataStation[10].date!.toDate().toString().split(' ')[1].split('.')[0]}',
              style: style),
        );
        break;
      case 20:
        text = RotatedBox(
          quarterTurns: 3,
          child: Text(
              '${dataStation[20].date!.toDate().toString().split(' ')[1].split('.')[0]}',
              style: style),
        );
        break;
      case 30:
        text = RotatedBox(
          quarterTurns: 3,
          child: Text(
              '${dataStation[30].date!.toDate().toString().split(' ')[1].split('.')[0]}',
              style: style),
        );
        break;
      case 40:
        text = RotatedBox(
          quarterTurns: 3,
          child: Text(
              '${dataStation[40].date!.toDate().toString().split(' ')[1].split('.')[0]}',
              style: style),
        );
        break;
      case 50:
        text = RotatedBox(
          quarterTurns: 3,
          child: Text(
              '${dataStation[50].date!.toDate().toString().split(' ')[1].split('.')[0]}',
              style: style),
        );
        break;
      case 60:
        text = RotatedBox(
          quarterTurns: 3,
          child: Text(
              '${dataStation[60].date!.toDate().toString().split(' ')[1].split('.')[0]}',
              style: style),
        );
        break;
      case 70:
        text = RotatedBox(
          quarterTurns: 3,
          child: Text(
              '${dataStation[70].date!.toDate().toString().split(' ')[1].split('.')[0]}',
              style: style),
        );
        break;
      case 80:
        text = RotatedBox(
          quarterTurns: 3,
          child: Text(
              '${dataStation[80].date!.toDate().toString().split(' ')[1].split('.')[0]}',
              style: style),
        );
        break;
      case 90:
        text = RotatedBox(
          quarterTurns: 3,
          child: Text(
              '${dataStation[90].date!.toDate().toString().split(' ')[1].split('.')[0]}',
              style: style),
        );
        break;
      case 99:
        text = RotatedBox(
          quarterTurns: 3,
          child: Text(
              '${dataStation[99].date!.toDate().toString().split(' ')[1].split('.')[0]}',
              style: style),
        );
        break;

      default:
        text = const Text('');
        break;
    }

    return SideTitleWidget(
      axisSide: meta.axisSide,
      space: 10,
      child: text,
    );
  }

  SideTitles get bottomTitles => SideTitles(
        showTitles: true,
        reservedSize: 70,
        interval: 1,
        getTitlesWidget: bottomTitleWidgets,
      );

  FlGridData get gridData => FlGridData(
      show: true,
      // horizontalInterval: 0.1,
      getDrawingHorizontalLine: (value) {
        return FlLine(
          color: Color.fromARGB(255, 124, 124, 124),
          strokeWidth: 1,
        );
      },
      drawHorizontalLine: true,
      drawVerticalLine: false);

  FlBorderData get borderData => FlBorderData(
        show: true,
        border: const Border(
          bottom: BorderSide(color: Color(0xff4e4965), width: 4),
          left: BorderSide(color: Colors.transparent),
          right: BorderSide(color: Colors.transparent),
          top: BorderSide(color: Colors.transparent),
        ),
      );

  LineChartBarData get lineChartBarData => LineChartBarData(
        isCurved: true,
        color: (element == 'h2s')
            ? Color(0xff4af699)
            : (element == 'co2')
                ? Color(0xFFEE2727)
                : Color(0xFFFFCF26),
        barWidth: 2,
        isStrokeCapRound: true,
        dotData: FlDotData(show: false),
        belowBarData: BarAreaData(
          show: false,
          color: const Color(0x00aa4cfc),
        ),
        spots: dataStation.reversed.map((e) {
          // print(e.date!.toDate().toString().split(' ')[1].split('.')[0]);
          return FlSpot(
              dataStation.indexOf(e).toDouble(),
              (element == 'h2s')
                  ? (double.parse(e.h2s!) >= 0)
                      ? (Random().nextDouble() * 1.9).toPrecision(3)
                      : 0
                  : (element == 'co2')
                      ? (double.parse(e.co2!) >= 0)
                          ? double.parse(e.co2!)
                          : 0
                      : (double.parse(e.co!) >= 0)
                          ? double.parse(e.co!)
                          : 0);
        }).toList(),
      );
}

class RealTimeLineChart extends StatelessWidget {
  RealTimeController controller = Get.put(RealTimeController());

  @override
  Widget build(BuildContext context) {
    return Obx(() => Column(
          children: [],
        ));
  }
}
