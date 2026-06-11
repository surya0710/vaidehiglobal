<h2>New Requirement Submission</h2>

<p><strong>Full Name:</strong> {{ $data['fullName'] }}</p>
<p><strong>Company:</strong> {{ $data['companyName'] }}</p>
<p><strong>Country:</strong> {{ $data['country'] }}</p>
<p><strong>Email:</strong> {{ $data['email'] }}</p>

<hr>

<p><strong>Product Cluster:</strong> {{ $data['productCluster'] }}</p>
<p><strong>Requirement Details:</strong></p>
<p>{{ $data['requirementDetails'] }}</p>

<hr>

<p><strong>Estimated Volume:</strong> {{ $data['estimatedVolume'] ?? 'N/A' }}</p>
<p><strong>Target Timeline:</strong> {{ $data['targetTimeline'] ?? 'N/A' }}</p>