import 'dart:async';
import 'package:flutter/material.dart';

void main() {
  runApp(const FreshCheckApp());
}

class FreshCheckApp extends StatelessWidget {
  const FreshCheckApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      title: 'FreshCheck',
      theme: ThemeData(useMaterial3: true),
      home: const FreshnessCalculatorScreen(),
    );
  }
}

class FreshnessCalculatorScreen extends StatefulWidget {
  const FreshnessCalculatorScreen({super.key});

  @override
  State<FreshnessCalculatorScreen> createState() =>
      _FreshnessCalculatorScreenState();
}

class _FreshnessCalculatorScreenState extends State<FreshnessCalculatorScreen> {
  // Preset rules (days)
  final Map<String, int> rules = {
    'Milk': 7,
    'Cheese': 10,
    'Chicken': 2,
    'Leftovers': 3,
    'Custom': 0,
  };

  String selectedType = 'Milk';
  DateTime openedDate = DateTime.now();
  int customDays = 5;

  Timer? _timer;
  DateTime now = DateTime.now();

  @override
  void initState() {
    super.initState();
    _timer = Timer.periodic(const Duration(seconds: 1), (_) {
      setState(() => now = DateTime.now());
    });
  }

  @override
  void dispose() {
    _timer?.cancel();
    super.dispose();
  }

  int get safeDays => selectedType == 'Custom' ? customDays : (rules[selectedType] ?? 0);

  DateTime get expiryDate => DateTime(
    openedDate.year,
    openedDate.month,
    openedDate.day,
  ).add(Duration(days: safeDays));

  Duration get remaining => expiryDate.difference(now);

  Color get statusColor {
    if (safeDays <= 0) return Colors.grey;
    if (remaining.inSeconds <= 0) return Colors.red;

    final totalSeconds = safeDays * 24 * 60 * 60;
    final leftSeconds = remaining.inSeconds;
    final ratio = leftSeconds / totalSeconds;

    if (ratio <= 0.5) return Colors.orange;
    return Colors.green;
  }

  String formatDuration(Duration d) {
    final isNegative = d.isNegative;
    final abs = d.abs();
    final days = abs.inDays;
    final hours = abs.inHours % 24;
    final minutes = abs.inMinutes % 60;
    final seconds = abs.inSeconds % 60;

    final text = '${days}d ${hours}h ${minutes}m ${seconds}s';
    return isNegative ? 'Expired by $text' : '$text left';
  }

  Future<void> pickOpenedDate() async {
    final picked = await showDatePicker(
      context: context,
      initialDate: openedDate,
      firstDate: DateTime(2020, 1, 1),
      lastDate: DateTime(2100, 12, 31),
    );
    if (picked != null) {
      setState(() => openedDate = picked);
    }
  }

  void reset() {
    setState(() {
      selectedType = 'Milk';
      openedDate = DateTime.now();
      customDays = 5;
    });
  }

  @override
  Widget build(BuildContext context) {
    final exp = expiryDate;
    final expText = '${exp.year}-${exp.month.toString().padLeft(2, '0')}-${exp.day.toString().padLeft(2, '0')}';

    final openedText =
        '${openedDate.year}-${openedDate.month.toString().padLeft(2, '0')}-${openedDate.day.toString().padLeft(2, '0')}';

    return Scaffold(
      appBar: AppBar(
        title: const Text('FreshCheck'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            // Inputs Card
            Card(
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.stretch,
                  children: [
                    const Text(
                      'Freshness Calculator',
                      style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                    ),
                    const SizedBox(height: 12),

                    // Item type
                    DropdownButtonFormField<String>(
                      key: ValueKey(selectedType), // keeps it updating correctly
                      initialValue: selectedType,
                      items: rules.keys
                          .map((k) => DropdownMenuItem(value: k, child: Text(k)))
                          .toList(),
                      onChanged: (v) => setState(() => selectedType = v ?? 'Milk'),
                      decoration: const InputDecoration(
                        labelText: 'Item Type',
                        border: OutlineInputBorder(),
                      ),
                    ),

                    const SizedBox(height: 12),

                    // Opened date
                    InkWell(
                      onTap: pickOpenedDate,
                      child: InputDecorator(
                        decoration: const InputDecoration(
                          labelText: 'Opened Date',
                          border: OutlineInputBorder(),
                        ),
                        child: Text(openedText),
                      ),
                    ),

                    const SizedBox(height: 12),

                    // Custom days
                    if (selectedType == 'Custom')
                      TextFormField(
                        initialValue: customDays.toString(),
                        keyboardType: TextInputType.number,
                        decoration: const InputDecoration(
                          labelText: 'Safe Days (Custom)',
                          border: OutlineInputBorder(),
                        ),
                        onChanged: (v) {
                          final parsed = int.tryParse(v);
                          setState(() => customDays = (parsed == null || parsed <= 0) ? 1 : parsed);
                        },
                      )
                    else
                      Text(
                        'Safe Days (Preset): $safeDays days',
                        style: const TextStyle(fontSize: 16),
                      ),
                  ],
                ),
              ),
            ),

            const SizedBox(height: 12),

            // Results Card
            Card(
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.stretch,
                  children: [
                    Row(
                      children: [
                        Expanded(
                          child: Container(
                            height: 10,
                            decoration: BoxDecoration(
                              color: statusColor,
                              borderRadius: BorderRadius.circular(999),
                            ),
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 12),
                    Text('Expiry Date: $expText', style: const TextStyle(fontSize: 16)),
                    const SizedBox(height: 6),
                    Text(
                      formatDuration(remaining),
                      style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                    ),
                    const SizedBox(height: 6),
                    Text('Safe Days: $safeDays'),
                  ],
                ),
              ),
            ),

            const Spacer(),

            // Reset button
            SizedBox(
              width: double.infinity,
              child: ElevatedButton.icon(
                onPressed: reset,
                icon: const Icon(Icons.refresh),
                label: const Text('Reset'),
              ),
            )
          ],
        ),
      ),
    );
  }
}
