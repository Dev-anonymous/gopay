// import 'package:data_table_2/data_table_2.dart';
// import 'package:flutter/material.dart';
// import 'package:get/get.dart';

// import '../../../constants.dart';

// class ListStation extends StatefulWidget {
//   ListStation({Key? key}) : super(key: key);

//   @override
//   State<ListStation> createState() => _ListStationState();
// }

// class _ListStationState extends State<ListStation> {
//   // StationController _stationController = Get.put(StationController());

//   String? value;

//   @override
//   void initState() {
//     super.initState();
//   }

//   @override
//   Widget build(BuildContext context) {
//     return Container(
//       padding: EdgeInsets.all(defaultPadding),
//       decoration: BoxDecoration(
//         color: secondaryColor,
//         borderRadius: const BorderRadius.all(Radius.circular(10)),
//       ),
//       child: Column(
//         crossAxisAlignment: CrossAxisAlignment.start,
//         children: [
//           SizedBox(
//             width: double.infinity,
//             child: Obx(() => 
//             // (_stationController.isStationsListLoaded)
//             //     ? (_stationController.stations.length > 0)
//             //         ? 
//                     DataTable2(
//                         // columnSpacing: defaultPadding,
//                         minWidth: 600,
//                         columnSpacing: 12,
//                         horizontalMargin: 12,
//                         columns: [
//                           DataColumn(
//                             label: Text("Code de la station"),
//                           ),
//                           DataColumn(
//                             label: Text("Designation"),
//                           ),
//                           DataColumn(
//                             label: Text("Emplacement / Zone"),
//                           ),
//                         ],
//                         rows: List.generate(
//                           _stationController.stations.length,
//                           (index) => recentFileDataRow(
//                               _stationController.stations[index]),
//                         ),
//                       )
//                     : Center(
//                         child: Padding(
//                           padding: const EdgeInsets.all(16.0),
//                           child: Text(
//                             "Aucune station ne vous est affectée",
//                             style: Theme.of(context).textTheme.bodyLarge,
//                           ),
//                         ),
//                       )
//                 : Container(
//                     height: 250,
//                     decoration: const BoxDecoration(
//                       borderRadius: BorderRadius.all(Radius.circular(18)),
//                       color: secondaryColor,
//                     ),
//                     child: Center(
//                       child: SizedBox(
//                         height: 60,
//                         width: 60,
//                         child: CircularProgressIndicator(),
//                       ),
//                     ))),
//           ),
//         ],
//       ),
//     );
//   }

//   DataRow recentFileDataRow(Map<String, dynamic> station) {
//     return DataRow(
//       cells: [
//         DataCell(Text(station["code"])),
//         DataCell(Text(station["designation"])),
//         DataCell(Text(station["emplacement"])),
//       ],
//     );
//   }
// }
